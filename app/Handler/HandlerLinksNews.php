<?php

namespace App\Handler;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class HandlerLinksNews
{
    private $url = "https://www.rbc.ru/";
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    function getLinksNews() {
        yield from $this->getLinks($this->getContent());
    }

    private function getContent() {
        return $this->client->request('GET', $this->url)->getBody()->getContents();
    }

    private function getLinks($content) {
        $crawler = new Crawler($content);
        $links = $crawler->filter('body #js_news_feed_banner a.js-news-feed-item');
        foreach($links as $item) {
            $item = new Crawler($item);
            $link = $item->attr('href');
            if(strpos($link, "http://traffic.rbc.ru") !== false || strpos($link, "http://glecoupe.mb.rbc.ru") !== false) {
                continue;
            }
            yield [$link, $this->getIdArticle($link)];
        }
    }

    private function getIdArticle($link) {
        $link = strtok($link, '?');
        return substr($link, (strripos( $link, "/",-1)) + 1);
    }
}

