<?php

use Src\Crawler\KingDomCrawler;
use Goutte\Client;

class testKingDomCrawler extends PHPUnit\Framework\TestCase
{

    /**
     * 擷取「書籍資料」
     */
    public function testGetBookInfo()
    {
        // Arrange
        $url     = 'https://www.kingstone.com.tw/basics/basics.asp?kmcode=2013120471221&actid=ActBillBoard';

        $client  = new Client();
        $crawler = new KingDomCrawler($client);
        $crawler->send($url)
            ->html();

        // Act
        $crawler->getBookInfo();

        // Assert
        $expected = [
            'ISBN'        => '9789864591374',
            'name'        => '不需要電腦的程式設計課：從遊戲中學習電腦語言、鍛鍊運算思維，培育AI時代必備的數位素養',
            'subTitle'    => 'How to Think Like a Coder without Even Trying',
            'author'      => '養姆．克利斯欽',
            'publishing'  => '積木      ',
            'publishDate' => '2018/6/7',
            'translator'  => '魏嘉儀',
        ];

        $this->assertEquals($expected, $crawler->getBookInfo());
    }
}