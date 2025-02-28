<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\News;
use Illuminate\Support\Facades\Log;
class NewsScraperService
{
    public function scrape($url)
{
    $response = Http::get($url);
    $html = $response->body();
    $crawler = new Crawler($html);

    $articles = $crawler->filter('.item-news')->each(function (Crawler $node) {
        Log::info('Node:', ['html' => $node->html()]); // Ghi log nội dung HTML của node

        return [
            'title' => $node->filter('h3.title-news a')->count() ? $node->filter('h3.title-news a')->text() : 'Không có tiêu đề',
            'link' => $node->filter('h3.title-news a')->count() ? $node->filter('h3.title-news a')->attr('href') : '#',
            'description' => $node->filter('p.description')->count() ? $node->filter('p.description')->text() : 'Không có mô tả',
            'time' => $node->filter('.time-ago, .time')->count() ? $node->filter('.time-ago, .time')->text() : 'Không có thời gian',
        ];
    });
    

    foreach ($articles as $article) {
        News::updateOrCreate(
            ['link' => $article['link']],
            $article
        );
    }

    return News::all();
}
}
