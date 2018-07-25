<?php

use Src\Crawler\KingStoneCrawler;
use Goutte\Client;

class testKingStoneCrawler extends PHPUnit\Framework\TestCase
{

    /**
     * 擷取「書籍資料」
     */
    public function testGetBookInfo()
    {
        // Arrange
        $kmcode = '2013120471221';

        $client  = new Client();
        $crawler = new KingStoneCrawler($client);

        // Act
        $bookInfo = $crawler->getBookInfo($kmcode);

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

        $this->assertEquals($expected, $bookInfo);
    }

    /**
     * 使用書籍名稱搜尋書籍，並擷取搜尋結果
     */
    public function testSearchBookByName()
    {
        $client  = new Client();
        $crawler = new KingStoneCrawler($client);

        $bookName = '不需要電腦的程式設計課：從遊戲中學習電腦語言';
        $books = $crawler->searchBook($bookName);

        $expected = [
            [
                'ISBN'        => '9789864591374',
                'name'        => '不需要電腦的程式設計課：從遊戲中學習電腦語言、鍛鍊運算思維，培育AI時代必備的數位素養',
                'subTitle'    => 'How to Think Like a Coder without Even Trying',
                'author'      => '養姆．克利斯欽',
                'publishing'  => '積木      ',
                'publishDate' => '2018/6/7',
                'translator'  => '魏嘉儀',
            ],
        ];

        $this->assertEquals($expected, $books);
    }
}