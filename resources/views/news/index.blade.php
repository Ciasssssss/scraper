<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức mới nhất</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Tin tức mới nhất</h1>
        <!-- Form tìm kiếm -->
        <form action="{{ route('news.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Nhập từ khóa..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <!-- Bộ lọc -->
        <form method="GET" action="{{ url('/news') }}" class="mb-4">
            <div class="row">
                <!-- Chọn danh mục -->
                <div class="col-md-4">
                    <select name="category" class="form-control">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Chọn ngày -->
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

                <!-- Nút lọc -->
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Lọc tin tức</button>
                </div>
            </div>
        </form>

        <!-- Danh sách tin tức -->
        <div class="row">
            @foreach($news as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ $item->image }}" class="card-img-top" alt="Hình ảnh">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text"><strong>Danh mục:</strong> {{ $item->category }}</p>
                            <p class="card-text"><strong>Ngày đăng:</strong> {{ $item->created_at->format('d/m/Y') }}</p>
                            <p class="card-text">{{ $item->description }}</p>
                            <a href="{{ $item->link }}" class="btn btn-primary" target="_blank">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Hiển thị phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $news->links() }}
        </div>
        @if($news->isEmpty())
            <p class="text-center text-muted">Không tìm thấy kết quả phù hợp.</p>
        @endif

    </div>
</body>
</html>
