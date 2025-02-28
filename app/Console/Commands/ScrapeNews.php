<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

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
    Log::info('Scraper bắt đầu chạy lúc: ' . now());

    $url = 'https://vnexpress.net/tin-tuc';
    $response = Http::get($url);

    if ($response->successful()) {
        Log::info('Lấy dữ liệu từ trang web thành công.');

        $html = $response->body();
        $crawler = new Crawler($html);

        $crawler->filter('h3.title-news a')->each(function ($node) {
            Log::info('Đang xử lý bài viết: ' . $node->text());

            $title = $node->text();
            $link = $node->count() > 0 ? $node->attr('href') : null;

            // Kiểm tra nếu có phần tử hình ảnh
            $imageNode = $node->ancestors()->filter('.thumb-art img');
            $image = $imageNode->count() > 0 ? ($imageNode->attr('data-src') ?? $imageNode->attr('src')) : null;

            // Kiểm tra nếu có danh mục
            $categoryNode = $node->ancestors()->filter('.parent-cate');
            $category = $categoryNode->count() > 0 ? $categoryNode->text() : 'Khác';

            // Kiểm tra tin đã tồn tại chưa
            if (!News::where('link', $link)->exists()) {
                News::create([
                    'title' => $title,
                    'link' => $link,
                    'image' => $image,
                    'category' => $category,
                    'description' => 'Mô tả tin tức',
                    'time' => now(),
                ]);
                Log::info('Lưu bài viết mới: ' . $title);
            }
        });

        $this->info('News scraping completed!');
        Log::info('Scraper hoàn tất lúc: ' . now());
    } else {
        Log::error('Lỗi: Không thể lấy dữ liệu từ trang web.');
    }
}

}
