<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsScraperService;

class NewsScraperController extends Controller
{
    protected $scraper;

    public function __construct(NewsScraperService $scraper)
    {
        $this->scraper = $scraper;
    }

    public function scrape()
    {
        $url = 'https://vnexpress.net'; // Thay đổi URL theo ý muốn
        $titles = $this->scraper->scrape($url);

        return response()->json($titles);
    }
}
