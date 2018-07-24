<?php

namespace Src\Crawler;

use Goutte\Client;

class KingStoneCrawler
{
    protected $client;
    protected $response;
    protected $url;
    protected $html;

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
     * @param string $url URL
     * @param string $method HTTP Methods
     *
     * @return $this
     */
    public function send($url, $method = 'GET')
    {
        $this->response = $this->client->request($method, $url);

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
     * @return array
     */
    public function getBookInfo()
    {
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
     * 擷取「ISBN 國際標準書號」
     *
     * @return mixed
     */
    public function getISBN()
    {
        return $this->htmlMatch('/<span>ISBN：<\/span><em>(\d+)<\/em>/');
    }

    /**
     * 擷取「書名」
     *
     * @return mixed
     */
    public function getBookName()
    {
        return $this->htmlMatch('/<h1>(.*)\r\n/');
    }

    /**
     * 擷取「作者」
     *
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->htmlMatch('/<span>作者：<em><a.*;">(.*)<\/a>/');
    }

    /**
     * 擷取「出版社」
     *
     * @return mixed
     */
    public function getPublishing()
    {
        return $this->htmlMatch('/<span>出版社：<em><a.*;">(.*)<\/a>/');
    }

    /**
     * 擷取「譯者」
     *
     * @return mixed
     */
    public function getTranslator()
    {
        return $this->htmlMatch('/<span>譯者：<\/span><em>(.*)<\/em>/');
    }

    /**
     * 擷取「副標題」
     *
     * @return mixed
     */
    public function getSubTitle()
    {
        return $this->htmlMatch('/<em><a href="#this".*;">(.*)<\/a><\/em>/');
    }

    /**
     * 擷取「出版日期」
     *
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->htmlMatch('/<span>出版日：<\/span><em>(.*)<\/em>/');
    }

    /**
     * 擷取 HTML 頁面資訊
     *
     * @param String $pattern 正規表示方式規則
     *
     * @return mixed|string
     */
    protected function htmlMatch($pattern)
    {
        $match = [];
        preg_match($pattern, $this->html, $match);

        return isset($match[1]) ? $match[1] : '';
    }
}