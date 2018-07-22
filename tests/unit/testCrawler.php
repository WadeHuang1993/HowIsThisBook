<?php
/**
 * Created by PhpStorm.
 * User: wade
 * Date: 2018/7/22
 * Time: 下午2:50
 */

use Goutte\Client;
use Sunra\PhpSimple\HtmlDomParser;


class testCrawler extends PHPUnit\Framework\TestCase
{
    public function testGetISBN(): void
    {
        // Arrange
        $client  = new Client();
        $url     = 'https://www.kingstone.com.tw/basics/basics.asp?kmcode=2013120471221&actid=ActBillBoard';
        $crawler = $client->request('GET', $url);
        $html  = $crawler->html();

        // Act
        $match = [];
        preg_match('/<span>ISBN：<\/span><em>(\d+)<\/em>/', $html, $match);

        // Assert
        $this->assertEquals($match[1], '9789864591374');
//        $dom = HtmlDomParser::str_get_html($html);
    }
}