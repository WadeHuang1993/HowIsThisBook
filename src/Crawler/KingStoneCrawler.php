<?php

namespace Src\Crawler;

use Goutte\Client;
use Sunra\PhpSimple\HtmlDomParser;

class KingStoneCrawler
{
    protected $client;
    protected $response;
    protected $url;
    protected $html;
    protected $baseUrl = 'https://www.kingstone.com.tw';

    /**
     * KingStoneCrawler 建構方法.
     *
     * @param Client $client HTTP Client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 發送爬蟲擷取頁面
     *
     * @param string $url        URL
     * @param string $method     HTTP Methods
     * @param array  $parameters HTTP 參數（GET 無法使用）
     *
     * @return $this
     */
    public function send(string $url, string $method = 'GET', array $parameters = [])
    {
        $this->response = $this->client->request($method, $url, $parameters);

        return $this;
    }

    /**
     * 取得爬蟲的 HTML 頁面
     *
     * @return mixed
     */
    public function html()
    {
        $this->html = $this->response->html();

        return $this->html;
    }

    /**
     * 擷取書籍資料
     *
     * 金石堂每本書籍都有一個 kmcode，
     * 進入書籍內容頁面也是依照 kmcode 來取得書籍資料。
     *
     * @param string $kmcode 金石堂書籍唯一碼
     *
     * @return array
     */
    public function getBookInfo(string $kmcode): array
    {
        $url = $this->baseUrl . '/basics/basics.asp?kmcode=' . $kmcode;
        $this->send($url)->html();

        return [
            'ISBN'        => $this->getISBN(),
            'name'        => $this->getBookName(),
            'subTitle'    => $this->getSubTitle(),
            'author'      => $this->getAuthor(),
            'publishing'  => $this->getPublishing(),
            'publishDate' => $this->getPublishDate(),
            'translator'  => $this->getTranslator(),
        ];
    }

    /**
     * 搜尋書籍
     *
     * 利用書籍名稱，至書籍網站搜尋書籍，
     * 若搜尋結果為多筆，則擷取多筆書籍資料。
     *
     * @param string $bookName 書籍名稱
     *
     * @return array 書籍資料（多筆）
     */
    public function searchBook(string $bookName = ''): array
    {
        // 金石堂做了兩次 urlencode
        $bookName = urlencode(urlencode($bookName));

        $url = $this->makeSearchUrl($bookName);
        $this->send($url);

        // 擷取書籍 kmcode
        $kmcodes = $this->extractSearchResult();

        // 擷取書籍內容
        $booksInfo = [];
        foreach ($kmcodes as $kmcode) {
            $booksInfo[] = $this->getBookInfo($kmcode);
        }

        return $booksInfo;
    }

    /**
     * 擷取搜尋結果
     *
     * 在搜尋結果列表中，先擷取書籍的連結（多個），再從連結擷取書籍的 kmcode，供擷取書籍內容用。
     *
     * @return array $kmcodes 書籍的 kmcode（多個）
     */
    protected function extractSearchResult(): array
    {
        $parser     = HtmlDomParser::str_get_html($this->html());
        $resultLink = $parser->find('.row_list > ul > li .anchor');

        $kmcodes = $this->extractKmcode($resultLink);

        return $kmcodes;
    }

    /**
     * 從搜尋結果列表中的連結擷取 金石堂書籍唯一碼
     *
     * @param array $resultsList 搜尋結果列表
     *
     * @return array
     */
    protected function extractKmcode(array $resultsLinks): array
    {
        $results = [];

        foreach ($resultsLinks as $link) {
            $href = html_entity_decode($link->attr['href']);

            // 金石堂的書籍唯一碼，可用來進入書本內容頁面
            $kmcode = $this->htmlMatch('/kmcode=(.*)&lid/', $href);

            // 過濾重複的 kmcode
            $results[$kmcode] = $kmcode;
        }

        return $results;
    }

    /**
     * 擷取「ISBN 國際標準書號」
     *
     * @return string
     */
    public function getISBN(): string
    {
        return $this->htmlMatch('/<span>ISBN：<\/span><em>(\d+)<\/em>/');
    }

    /**
     * 擷取「書名」
     *
     * @return string
     */
    public function getBookName(): string
    {
        return $this->htmlMatch('/<h1>(.*)\r\n/');
    }

    /**
     * 擷取「作者」
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->htmlMatch('/<span>作者：<em><a.*;">(.*)<\/a>/');
    }

    /**
     * 擷取「出版社」
     *
     * @return string
     */
    public function getPublishing(): string
    {
        return $this->htmlMatch('/<span>出版社：<em><a.*;">(.*)<\/a>/');
    }

    /**
     * 擷取「譯者」
     *
     * @return string
     */
    public function getTranslator(): string
    {
        return $this->htmlMatch('/<span>譯者：<\/span><em>(.*)<\/em>/');
    }

    /**
     * 擷取「副標題」
     *
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->htmlMatch('/<em><a href="#this".*;">(.*)<\/a><\/em>/');
    }

    /**
     * 擷取「出版日期」
     *
     * @return string
     */
    public function getPublishDate(): string
    {
        return $this->htmlMatch('/<span>出版日：<\/span><em>(.*)<\/em>/');
    }

    /**
     * 擷取 HTML 頁面資訊
     *
     * @param string $pattern 正規表示方式規則
     * @param string $html 被查詢對象
     *
     * @return string
     */
    protected function htmlMatch(string $pattern, string $html = ''): string
    {
        $html = (trim($html) != '') ? $html : $this->html();

        $match = [];
        preg_match($pattern, $html, $match);

        return isset($match[1]) ? $match[1] : '';
    }

    /**
     * @param $bookName
     *
     * @return string
     */
    protected function makeSearchUrl(string $bookName): string
    {
        $url = $this->baseUrl . '/search/result.asp';
        $url .= '?c_name=' . $bookName;
        $url .= '&se_type=4';

        return $url;
    }
}