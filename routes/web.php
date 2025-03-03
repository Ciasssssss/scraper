<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsScraperController;
use App\Http\Controllers\NewsController;

Route::get('/', function () {return view('welcome');});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::get('/scrape-news', [NewsScraperController::class, 'scrape']);
