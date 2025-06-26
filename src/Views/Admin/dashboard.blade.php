@extends('layouts.master')

@section('title', 'Trang chủ - Dashboard')

@section('content')
<style>
    .dashboard-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    #content{
        padding: 15px;
    }
    
    .overview-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .overview-card:hover {
        transform: translateY(-5px);
    }
    
    .overview-card h3 {
        margin: 0;
        font-size: 2.5em;
        font-weight: bold;
    }
    
    .overview-card p {
        margin: 10px 0 0 0;
        font-size: 1.1em;
        opacity: 0.9;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stats-box {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stats-box h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #667eea;
        padding-bottom: 10px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    
    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    tr:hover {
        background-color: #e9ecef;
    }
    
    .status-pending { color: #ffc107; font-weight: bold; }
    .status-approved { color: #28a745; font-weight: bold; }
    .status-rejected { color: #dc3545; font-weight: bold; }
    .status-returned { color: #6c757d; font-weight: bold; }
    
    .recent-items {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .rating-stars {
        color: #ffc107;
    }
    
    .empty-data {
        text-align: center;
        color: #6c757d;
        font-style: italic;
    }
</style>

<!-- Tổng quan -->
<div class="dashboard-overview">
    <div class="overview-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h3>{{ $totalBooks }}</h3>
        <p>Tổng số sách</p>
    </div>
    <div class="overview-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <h3>{{ $totalUsers }}</h3>
        <p>Người dùng</p>
    </div>
    <div class="overview-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <h3>{{ $totalBorrowings }}</h3>
        <p>Lượt mượn sách</p>
    </div>
    <div class="overview-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <h3>{{ $totalCategories }}</h3>
        <p>Danh mục sách</p>
    </div>
    <div class="overview-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <h3>{{ $totalPosts }}</h3>
        <p>Bài viết</p>
    </div>
    <div class="overview-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
        <h3>{{ $totalShelves }}</h3>
        <p>Kệ sách</p>
    </div>
</div>

<!-- Thống kê chính -->
<div class="stats-grid">
    <!-- Sách theo danh mục -->
    <div class="stats-box">
        <h2>📚 Sách theo danh mục</h2>
        <table>
            <tr>
                <th>Danh mục</th>
                <th>Số lượng sách</th>
            </tr>
            @forelse ($booksByCategory as $item)
                <tr>
                    <td>{{ $item['category_name'] ?? 'Không có danh mục' }}</td>
                    <td><strong>{{ $item['book_count'] }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty-data">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Trạng thái mượn sách -->
    <div class="stats-box">
        <h2>📋 Trạng thái mượn sách</h2>
        <table>
            <tr>
                <th>Trạng thái</th>
                <th>Số lượng</th>
            </tr>
            @forelse ($borrowingsByStatus as $item)
                <tr>
                    <td>
                        <span class="status-{{ $item['status'] }}">
                            @switch($item['status'])
                                @case('pending') Chờ duyệt @break
                                @case('approved') Đã duyệt @break
                                @case('rejected') Từ chối @break
                                @case('returned') Đã trả @break
                                @default {{ $item['status'] }}
                            @endswitch
                        </span>
                    </td>
                    <td><strong>{{ $item['borrowing_count'] }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty-data">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Người dùng theo vai trò -->
    <div class="stats-box">
        <h2>👥 Người dùng theo vai trò</h2>
        <table>
            <tr>
                <th>Vai trò</th>
                <th>Số lượng</th>
            </tr>
            @forelse ($usersByRole as $item)
                <tr>
                    <td>
                        @switch($item['role'])
                            @case('admin') Quản trị viên @break
                            @case('auth') Thủ thư @break
                            @case('user') Người dùng @break
                            @default {{ $item['role'] }}
                        @endswitch
                    </td>
                    <td><strong>{{ $item['user_count'] }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty-data">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Kệ sách có sách -->
    <div class="stats-box">
        <h2>🏠 Kệ sách có sách</h2>
        <table>
            <tr>
                <th>Tên kệ</th>
                <th>Số sách</th>
            </tr>
            @forelse ($shelvesWithBooks as $item)
                <tr>
                    <td>{{ $item['shelf_name'] }}</td>
                    <td><strong>{{ $item['book_count'] }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty-data">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>
</div>

<!-- Thống kê chi tiết -->
<div class="stats-grid">
    <!-- Sách được mượn nhiều nhất -->
    <div class="stats-box">
        <h2>🏆 Top sách được mượn nhiều</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Lượt mượn</th>
                </tr>
                @forelse ($mostBorrowedBooks as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['author'] }}</td>
                        <td><strong>{{ $item['borrow_count'] }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty-data">Chưa có dữ liệu mượn sách</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>

    <!-- Người dùng mượn nhiều nhất -->
    <div class="stats-box">
        <h2>⭐ Top người dùng tích cực</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Họ tên</th>
                    <th>Username</th>
                    <th>Lượt mượn</th>
                </tr>
                @forelse ($mostActiveBorrowers as $item)
                    <tr>
                        <td>{{ $item['full_name'] }}</td>
                        <td>{{ $item['username'] }}</td>
                        <td><strong>{{ $item['borrow_count'] }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty-data">Chưa có dữ liệu mượn sách</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>

    <!-- Sách mới nhất -->
    <div class="stats-box">
        <h2>📖 Sách mới nhất</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Danh mục</th>
                    <th>Ngày thêm</th>
                </tr>
                @forelse ($latestBooks as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['author'] }}</td>
                        <td>{{ $item['category_name'] ?? 'N/A' }}</td>
                        <td>{{ date('d/m/Y', strtotime($item['created_at'])) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-data">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>

    <!-- Mượn sách gần đây -->
    <div class="stats-box">
        <h2>🕒 Mượn sách gần đây</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Người mượn</th>
                    <th>Sách</th>
                    <th>Trạng thái</th>
                    <th>Ngày mượn</th>
                </tr>
                @forelse ($recentBorrowings as $item)
                    <tr>
                        <td>{{ $item['full_name'] }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>
                            <span class="status-{{ $item['status'] }}">
                                @switch($item['status'])
                                    @case('pending') Chờ duyệt @break
                                    @case('approved') Đã duyệt @break
                                    @case('rejected') Từ chối @break
                                    @case('returned') Đã trả @break
                                    @default {{ $item['status'] }}
                                @endswitch
                            </span>
                        </td>
                        <td>{{ date('d/m/Y', strtotime($item['created_at'])) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-data">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>
</div>

<!-- Thống kê bổ sung -->
<div class="stats-grid">
    @if(count($booksByRating) > 0)
    <!-- Sách theo đánh giá -->
    <div class="stats-box">
        <h2>⭐ Sách theo đánh giá</h2>
        <table>
            <tr>
                <th>Tiêu đề sách</th>
                <th>Đánh giá trung bình</th>
                <th>Số lượt đánh giá</th>
            </tr>
            @foreach ($booksByRating as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>
                        <span class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if($i <= floor($item['avg_rating']))
                                    ★
                                @elseif($i <= $item['avg_rating'])
                                    ☆
                                @else
                                    ☆
                                @endif
                            @endfor
                        </span>
                        ({{ number_format($item['avg_rating'], 1) }})
                    </td>
                    <td><strong>{{ $item['rating_count'] }}</strong></td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Sách theo kệ -->
    @if(count($booksByShelf) > 0)
    <div class="stats-box">
        <h2>📚 Sách theo kệ</h2>
        <table>
            <tr>
                <th>Tên kệ</th>
                <th>Số lượng sách</th>
            </tr>
            @foreach ($booksByShelf as $item)
                <tr>
                    <td>{{ $item['shelf_name'] ?? 'Chưa xếp kệ' }}</td>
                    <td><strong>{{ $item['book_count'] }}</strong></td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Sách theo năm xuất bản -->
    @if(count($booksByYear) > 0)
    <div class="stats-box">
        <h2>📅 Sách theo năm xuất bản</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Năm xuất bản</th>
                    <th>Số lượng sách</th>
                </tr>
                @foreach ($booksByYear as $item)
                    <tr>
                        <td>{{ $item['publish_year'] }}</td>
                        <td><strong>{{ $item['book_count'] }}</strong></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Sách nổi bật -->
    @if(count($featuredBooks) > 0)
    <div class="stats-box">
        <h2>🌟 Sách nổi bật</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Danh mục</th>
                    <th>Ngày thêm</th>
                </tr>
                @foreach ($featuredBooks as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['author'] }}</td>
                        <td>{{ $item['category_name'] ?? 'N/A' }}</td>
                        <td>{{ date('d/m/Y', strtotime($item['created_at'])) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Thống kê người dùng và bài viết -->
<div class="stats-grid">
    <!-- Người dùng mới nhất -->
    @if(count($latestUsers) > 0)
    <div class="stats-box">
        <h2>👤 Người dùng mới nhất</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Họ tên</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày đăng ký</th>
                </tr>
                @foreach ($latestUsers as $item)
                    <tr>
                        <td>{{ $item['full_name'] }}</td>
                        <td>{{ $item['username'] }}</td>
                        <td>{{ $item['email'] }}</td>
                        <td>
                            @switch($item['role'])
                                @case('admin') Quản trị viên @break
                                @case('auth') Thủ thư @break
                                @case('user') Người dùng @break
                                @default {{ $item['role'] }}
                            @endswitch
                        </td>
                        <td>{{ date('d/m/Y', strtotime($item['created_at'])) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Bài viết theo trạng thái -->
    @if(count($postsByStatus) > 0)
    <div class="stats-box">
        <h2>📄 Bài viết theo trạng thái</h2>
        <table>
            <tr>
                <th>Trạng thái</th>
                <th>Số lượng</th>
            </tr>
            @foreach ($postsByStatus as $item)
                <tr>
                    <td>
                        @switch($item['status'])
                            @case('published') Đã xuất bản @break
                            @case('draft') Bản nháp @break
                            @case('pending') Chờ duyệt @break
                            @default {{ $item['status'] }}
                        @endswitch
                    </td>
                    <td><strong>{{ $item['post_count'] }}</strong></td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Bài viết mới nhất -->
    @if(count($latestPosts) > 0)
    <div class="stats-box">
        <h2>📰 Bài viết mới nhất</h2>
        <div class="recent-items">
            <table>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Tác giả</th>
                    <th>Danh mục</th>
                    <th>Ngày tạo</th>
                </tr>
                @foreach ($latestPosts as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['author_name'] ?? 'N/A' }}</td>
                        <td>{{ $item['category_name'] ?? 'N/A' }}</td>
                        <td>{{ date('d/m/Y', strtotime($item['created_at'])) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
</div>

@endsection