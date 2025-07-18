@extends('layouts.master')
@section('title')
    Đọc Online: {{ $book['title'] }}
@endsection
@section('content')
<style>
    .book-read-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .book-read-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 40px;
        animation: fadeInUp 0.6s ease;
    }

    .book-read-header {
        padding: 24px;
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        text-align: center;
    }

    .book-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .book-author {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .book-content {
        padding: 40px;
        line-height: 1.8;
        font-size: 1.1rem;
        color: #333;
        background: #f5f7fa;
        border-radius: 0 0 16px 16px;
    }

    .book-content p {
        margin-bottom: 16px;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 16px;
        padding: 24px;
        background: rgba(255, 255, 255, 0.95);
    }

    .btn {
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-size: 1rem;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-secondary {
        background: linear-gradient(45deg, #a8edea, #fed6e3);
        color: #333;
        box-shadow: 0 8px 25px rgba(168, 237, 234, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(168, 237, 234, 0.4);
    }

    @media (max-width: 768px) {
        .book-read-container {
            padding: 15px;
        }

        .book-title {
            font-size: 1.6rem;
        }

        .book-author {
            font-size: 1rem;
        }

        .book-content {
            padding: 20px;
            font-size: 1rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="book-read-container">
    <!-- Book Reading Card -->
    <div class="book-read-card">
        <!-- Book Header -->
        <div class="book-read-header">
            <h1 class="book-title">{{ $book['title'] }}</h1>
            <p class="book-author">Tác giả: {{ $book['author'] }}</p>
        </div>

        <!-- Book Content -->
        <div class="book-content">
            @if($book['content'])
               {!! $book['content'] !!}
            @else
                <p>Nội dung sách hiện không khả dụng. Vui lòng thử lại sau.</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="/book-detail/{{ $book['id'] }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay lại chi tiết sách
            </a>
        </div>
    </div>
</div>
@endsection