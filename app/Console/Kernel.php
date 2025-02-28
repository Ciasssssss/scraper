<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScrapeNews;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('scrape:news')->everyMinute()
                ->appendOutputTo(storage_path('logs/scraper.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');  // Đảm bảo lệnh nằm trong thư mục này
        require base_path('routes/console.php');
    }
}