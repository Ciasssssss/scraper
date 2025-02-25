<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeNews extends Command
{
    protected $signature = 'scrape:news'; // Lệnh để chạy trong terminal
    protected $description = 'Scrape news from a website and save to database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
{
    $url = 'https://vnexpress.net/tin-tuc'; // Đổi sang trang bạn muốn scrape
    $response = Http::get($url);

    if ($response->successful()) {
        $html = $response->body();
        $crawler = new Crawler($html);

        $crawler->filter('.title-news a')->each(function ($node) {
            $title = $node->text();
            $link = $node->attr('href');
            $image = $node->filter('.thumb-art img')->attr('data-src') ?? $node->filter('.thumb-art img')->attr('src');
            $category = $node->filter('.parent-cate')->text() ?? 'Khác'; // Lấy danh mục nếu có

            // Kiểm tra tin đã tồn tại chưa
            if (!News::where('link', $link)->exists()) {
                News::create([
                    'title' => $title,
                    'link' => $link,
                    'image' => $image, // Lưu ảnh vào database
                    'category' => $category,
                    'description' => 'Mô tả tin tức',
                    'time' => now(),
                ]);
            }
        });

        $this->info('News scraping completed!');
    } else {
        $this->error('Failed to fetch news.');
    }
}

}
