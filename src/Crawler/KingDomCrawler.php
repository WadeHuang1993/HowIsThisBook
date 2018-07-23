<?php

namespace Src\Crawler;

use Goutte\Client;

class KingDomCrawler
{
    protected $client;
    protected $response;
    protected $url;
    protected $html;

    /**
     * KingDomCrawler 建構方法.
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
     * @param string $url    Target Url
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
        $match = [];
        preg_match('/<span>ISBN：<\/span><em>(\d+)<\/em>/', $this->html, $match);

        return $match[1];
    }

    /**
     * 擷取「書名」
     *
     * @return mixed
     */
    public function getBookName()
    {
        $match = [];
        preg_match('/<h1>(.*)\r\n/', $this->html, $match);

        return $match[1];
    }

    /**
     * 擷取「作者」
     *
     * @return mixed
     */
    public function getAuthor()
    {
        $match = [];
        preg_match('/<span>作者：<em><a.*;">(.*)<\/a>/', $this->html, $match);

        return $match[1];
    }

    /**
     * 擷取「出版社」
     *
     * @return mixed
     */
    public function getPublishing()
    {
        $match = [];
        preg_match('/<span>出版社：<em><a.*;">(.*)<\/a>/', $this->html, $match);

        return $match[1];
    }

    /**
     * 擷取「譯者」
     *
     * @return mixed
     */
    public function getTranslator()
    {
        $match = [];
        preg_match('/<span>譯者：<\/span><em>(.*)<\/em>/', $this->html, $match);

        return $match[1];
    }

    /**
     * 擷取「副標題」
     *
     * @return mixed
     */
    public function getSubTitle()
    {
        $match = [];
        preg_match('/<em><a href="#this".*;">(.*)<\/a><\/em>/', $this->html, $match);

        return $match[1];
    }

    /**
     * 擷取「出版日期」
     *
     * @return mixed
     */
    public function getPublishDate()
    {
        $match = [];
        preg_match('/<span>出版日：<\/span><em>(.*)<\/em>/', $this->html, $match);

        return $match[1];
    }
}