@extends('layouts.master')

@section('title', 'Trang chủ - Dashboard')

@section('content')
<style>
    .stats-container { display: flex; justify-content: space-around; }
    .stats-box { border: 1px solid #ccc; padding: 20px; margin: 10px; width: 30%; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>

<div class="stats-container">
    <!-- Thống kê theo danh mục -->
    <div class="stats-box">
        <h2>Sách theo danh mục</h2>
        <table>
            <tr>
                <th>Danh mục</th>
                <th>Số lượng sách</th>
            </tr>
            @forelse ($booksByCategory as $item)
                <tr>
                    <td>{{ $item['category_name'] ?? 'Không có danh mục' }}</td>
                    <td>{{ $item['book_count'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Thống kê theo đánh giá -->
    <div class="stats-box">
        <h2>Sách theo đánh giá</h2>
        <table>
            <tr>
                <th>Tiêu đề sách</th>
                <th>Đánh giá TB</th>
                <th>Số đánh giá</th>
            </tr>
            @forelse ($booksByRating as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ number_format($item['avg_rating'], 2) }}</td>
                    <td>{{ $item['rating_count'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Thống kê theo vị trí kệ -->
    <div class="stats-box">
        <h2>Sách theo vị trí kệ</h2>
        <table>
            <tr>
                <th>Vị trí kệ</th>
                <th>Số lượng sách</th>
            </tr>
            @forelse ($booksByShelf as $item)
                <tr>
                    <td>{{ $item['shelf_position_id'] }}</td>
                    <td>{{ $item['book_count'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Không có dữ liệu</td>
                </tr>
            @endforelse
        </table>
    </div>
</div>
@endsection