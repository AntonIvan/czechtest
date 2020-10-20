<?php

namespace App\Handler;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Handler\HandlerDB;

class HandlerPageNews
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->db = new HandlerDB();
    }

    public function getPageNews() {
        foreach (resolve(HandlerLinksNews::class)->getLinksNews() as $linksNew) {
            $this->db->save($this->getTextNews($this->getHTML($linksNew[0]), $linksNew[1]));
        }
    }

    private function getTextNews($html, $id) {
        $crawler = new Crawler($html);
            if($crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__header__title')->count() !== 0) {
                $name = $crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__header__title')->text();
            } else {
                $name = $crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__header')->text();
            }

            $texts = $crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__text p');
            $image = '';
            if($crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__main-image img')->count() !== 0) {
                $image = $crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__main-image img')->attr('src');
            }
            $textContent = '';
            foreach ($texts as $key => $text) {
                $str = $text->textContent;
                $PCREpattern  =  '/\r\n|\r|\n|\s\s+/u';
                $textContent .= preg_replace($PCREpattern, '', $str)."\n";

            }
            if($image == '' && $crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__picture__wrap img')->count() !== 0) {
                $image = $crawler->filter('.js-rbcslider-slide.rbcslider__slide[data-id="'.$id.'"] .article__picture__wrap img')->attr('src');
            }

        return ['name' => $name, 'text' => $textContent, 'image' => $image];
    }

    private function getHTML($url) {
        return $this->client->request('GET', $url)->getBody()->getContents();
    }
}


