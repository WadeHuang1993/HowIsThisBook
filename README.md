# 好書客 How is this book

![](https://github.com/WadeHuang1993/HowIsThisBook/blob/master/public/images/readme_banner.png)
![](https://img.shields.io/github/last-commit/WadeHuang1993/HowIsThisBook.svg)
![](https://img.shields.io/travis/php-v/symfony/symfony.svg)
![](https://img.shields.io/badge/Laravel-5.6-f4645f.svg)

好書客，一個分享讀書心得的平台。

## 網站需求：

[詳細線上需求文件在 DokuWiki](http://www.utools.info/code_wiki/doku.php?id=howisthisbook)

[工作項目分配在 KanbanFlow](https://kanbanflow.com/board/MoDo3Cqr)

平台提供書籍列表與內容，供匿名/會員（待討論）讀者發表讀書心得。

不同於求職天眼通，「好書客」提供一個平台，讓讀者反映真實的書籍心得。

讀書心得可為：
1. 好評 & 壞評
2. 書籍難易度
3. 適合什麼階段的人去看
4. 適合什麼職業

EX: Android 程式設計實例入門 這本書
1. 適合初學者
2. 適合學生、手機開發者等職業

### 首頁
- [ ] 搜尋書籍匡
- [ ] 熱門評論列（點擊列表進入「書本心得」頁面）

### 搜尋頁面
- [ ] 搜尋書籍匡
- [ ] 搜尋結果列表（點擊列表進入「書本心得」頁面）

### 書本心得
- [ ] 書本資訊
- [ ] 心得列表


## 技術規格：

書籍資料來源：
1. 串接書籍網站 API （博客來、天龍等）。
2. 寫爬蟲去爬資料（假設沒辦法串接 API 的話）。

爬蟲相關：

爬蟲目標，多家書商輪替爬。
1. 使用者搜尋時再爬

後端：

Apache + Laravel + PHP7。

前端：

未定。

## 套件

後端套件：
  * .env 切換工作環境設定檔案
  * deploy 程式部署工具
  * migration 資料庫遷移程式

## 建站步驟
  1. 設置好需求環境: php >= 7.1。
  2. 根據 Laravel 執行環境，從 env 目錄複製對應的 xxxx.env 設定檔案至根目錄，並改名為 .env。
  3. 開放檔案目錄更改權限，開放目錄如下:
     * storage。
     * bootstrap/cache
  4. 至根目錄執行 composer 安裝指令。 `composer install`
