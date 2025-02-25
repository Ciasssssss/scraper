<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
{
    $query = News::query();

    // Lọc theo danh mục
    if ($request->has('category') && $request->category != '') {
        $query->where('category', $request->category);
    }
    
    // Tìm kiếm theo từ khóa
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'LIKE', '%' . $request->search . '%')
              ->orWhere('description', 'LIKE', '%' . $request->search . '%');
    }

    // Lọc theo ngày
    if ($request->has('date') && $request->date != '') {
        $query->whereDate('time', $request->date);
    }

    // Thêm pagination (10 tin tức mỗi trang)
    $news = $query->orderBy('time', 'desc')->paginate(10);
    $categories = News::select('category')->distinct()->pluck('category');

    return view('news.index', compact('news', 'categories'));
}

}
