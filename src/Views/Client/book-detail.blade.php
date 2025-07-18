@extends('layouts.master')

@section('title')
    {{ $book['title'] }} - Chi tiết sách
@endsection

@section('content')
    <style>
        .book-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .book-detail-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 40px;
            animation: fadeInUp 0.6s ease;
        }

        .book-main-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
            padding: 40px;
        }

        .book-cover-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .cover-container {
            position: relative;
            width: 100%;
            max-width: 300px;
            perspective: 1000px;
        }

        .book-cover {
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            transition: transform 0.6s ease;
            cursor: pointer;
        }

        .book-cover:hover {
            transform: rotateY(10deg) rotateX(5deg) scale(1.02);
        }

        .cover-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }

        .cover-label {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 8px;
            font-weight: 500;
        }

        .book-info-section {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .book-meta {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .meta-item.green {
            border-left-color: #4ade80;
        }

        .meta-item.purple {
            border-left-color: #a855f7;
        }

        .meta-item.orange {
            border-left-color: #f97316;
        }

        .meta-item.red {
            border-left-color: #ef4444;
        }

        .meta-item.blue {
            border-left-color: #3b82f6;
        }

        .meta-icon {
            font-size: 18px;
            color: #667eea;
            margin-right: 12px;
            width: 20px;
        }

        .meta-icon.green {
            color: #4ade80;
        }

        .meta-icon.purple {
            color: #a855f7;
        }

        .meta-icon.orange {
            color: #f97316;
        }

        .meta-icon.red {
            color: #ef4444;
        }

        .meta-icon.blue {
            color: #3b82f6;
        }

        .meta-label {
            font-weight: 600;
            color: #333;
            margin-right: 8px;
        }

        .meta-value {
            color: #666;
            flex: 1;
        }

        .book-summary {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 32px;
        }

        .summary-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .summary-text {
            line-height: 1.7;
            color: #555;
            font-size: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
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

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(79, 172, 254, 0.4);
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

        .btn-disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .related-books {
            margin-top: 60px;
            animation: fadeInUp 0.6s ease 0.2s both;
        }

        .related-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: #000
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .book-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .book-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-card-image {
            transform: scale(1.05);
        }

        .book-card-content {
            padding: 20px;
        }

        .book-card-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-card-author {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .book-card-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color 0.3s ease;
        }

        .book-card-link:hover {
            color: #764ba2;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            display: none;
        }

        .notification.show {
            transform: translateX(0);
            display: block;
        }

        @media (max-width: 768px) {
            .book-main-content {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 24px;
            }

            .book-title {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }

            .book-meta {
                grid-template-columns: 1fr;
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

    <div class="book-detail-container">
        <!-- Main Book Detail Card -->
        <div class="book-detail-card">
            <div class="book-main-content">
                <!-- Book Cover Section -->
                <div class="book-cover-section">
                    <div class="cover-container">
                        <img src="{{ $book['cover_front'] ? '/' . $book['cover_front'] : '/assets/images/no-image.jpg' }}"
                            alt="Bìa trước - {{ $book['title'] }}" class="book-cover" id="bookCover">
                        <div class="cover-badge">
                            <i class="fas fa-star"></i> Nổi bật
                        </div>
                    </div>
                    <p class="cover-label">Bìa trước</p>

                    @if($book['cover_back'])
                        <div class="cover-container">
                            <img src="/{{ $book['cover_back'] }}" alt="Bìa sau - {{ $book['title'] }}" class="book-cover">
                        </div>
                        <p class="cover-label">Bìa sau</p>
                    @endif
                </div>

                <!-- Book Information Section -->
                <div class="book-info-section">
                    <div>
                        <h1 class="book-title">{{ $book['title'] }}</h1>

                        <!-- Book Metadata -->
                        <div class="book-meta">
                            <div class="meta-item">
                                <i class="fas fa-user meta-icon"></i>
                                <span class="meta-label">Tác giả:</span>
                                <span class="meta-value">{{ $book['author'] }}</span>
                            </div>

                            <div class="meta-item green">
                                <i class="fas fa-tags meta-icon green"></i>
                                <span class="meta-label">Thể loại:</span>
                                <span class="meta-value">{{ $book['category_name'] ?: 'Chưa phân loại' }}</span>
                            </div>

                            <div class="meta-item purple">
                                <i class="fas fa-calendar meta-icon purple"></i>
                                <span class="meta-label">Năm xuất bản:</span>
                                <span class="meta-value">{{ $book['publish_year'] ?: 'Không rõ' }}</span>
                            </div>

                            @if($book['isbn'])
                                <div class="meta-item orange">
                                    <i class="fas fa-barcode meta-icon orange"></i>
                                    <span class="meta-label">ISBN:</span>
                                    <span class="meta-value">{{ $book['isbn'] }}</span>
                                </div>
                            @endif

                            @if($book['shelf_position_title'])
                                <div class="meta-item red">
                                    <i class="fas fa-map-marker-alt meta-icon red"></i>
                                    <span class="meta-label">Vị trí:</span>
                                    <span class="meta-value">{{ $book['shelf_position_title'] }}</span>
                                </div>
                            @endif

                            @if($book['location_description'])
                                <div class="meta-item blue">
                                    <i class="fas fa-info-circle meta-icon blue"></i>
                                    <span class="meta-label">Mô tả vị trí:</span>
                                    <span class="meta-value">{{ $book['location_description'] }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Book Summary -->
                        @if($book['summary'])
                            <div class="book-summary">
                                <h3 class="summary-title">
                                    <i class="fas fa-file-alt"></i>
                                    Tóm tắt nội dung
                                </h3>
                                <p class="summary-text">{!! $book['summary'] !!}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @if($book['content'])
                            <a href="/book/read/{{ $book['id'] }}" class="btn btn-primary">
                                <i class="fas fa-book-open"></i>
                                Đọc sách online
                            </a>
                        @else
                            <div class="btn btn-disabled">
                                <i class="fas fa-book-open"></i>
                                Chưa có nội dung đọc online
                            </div>
                        @endif

                        <button class="btn btn-success" data-toggle="modal" data-target="#reserveModal"
                            onclick="openReserveModal({{ $book['id'] }})">
                            <i class="fas fa-bookmark"></i>
                            Đặt sách
                        </button>

                        <a href="javascript:history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Books Section -->
        @if(count($relatedBooks) > 0)
            <div class="related-books">
                <h2 class="related-title">
                    <i class="fas fa-books"></i>
                    Sách cùng thể loại
                </h2>
                <div class="books-grid">
                    @foreach($relatedBooks as $relatedBook)
                        <div class="book-card">
                            <img src="{{ $relatedBook['cover_front'] ? '/' . $relatedBook['cover_front'] : '/assets/images/no-image.jpg' }}"
                                alt="{{ $relatedBook['title'] }}" class="book-card-image">
                            <div class="book-card-content">
                                <h3 class="book-card-title">{{ $relatedBook['title'] }}</h3>
                                <p class="book-card-author">{{ $relatedBook['author'] }}</p>
                                <a href="/book-detail/{{ $relatedBook['id'] }}" class="book-card-link">
                                    Xem chi tiết <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notificationText">Thông báo</span>
    </div>
    <div class="modal fade" id="reserveModal" tabindex="-1" role="dialog" aria-labelledby="reserveModalLabel"
        aria-hidden="true" data-backdrop="true" data-keyboard="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">Đặt Sách</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reserveForm">
                        <div class="form-group">
                            <label for="borrowDate">Ngày Mượn</label>
                            <input type="date" class="form-control" id="borrowDate" required value="">
                        </div>
                        <div class="form-group">
                            <label for="returnDate">Ngày Trả</label>
                            <input type="date" class="form-control" id="returnDate" required >
                        </div>
                        <input type="hidden" id="bookId" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="submitReserve()">Xác Nhận</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var bookId = {{ $book['id'] }};

        function openReserveModal(bookId) {
            $('#bookId').val(bookId);

            const today = new Date();
            const borrowDate = today.toISOString().split('T')[0];
            $('#borrowDate').val(borrowDate);

            $('#reserveModal').modal({
                backdrop: true,
                keyboard: true
            }).modal('show');
        }

        $('#reserveModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        });

        $('#reserveModal').on('show.bs.modal', function () {
            $('body').addClass('modal-open');
        });

        function submitReserve() {
            const bookId = parseInt($('#bookId').val());
            const borrowDate = $('#borrowDate').val();
            const returnDate = $('#returnDate').val();

            if (!borrowDate || !returnDate) {
                showNotification('Vui lòng chọn ngày mượn và ngày trả.', 'error');
                return;
            }

            if (new Date(returnDate) <= new Date(borrowDate)) {
                showNotification('Ngày trả phải sau ngày mượn.', 'error');
                return;
            }

            $('#reserveModal').modal('hide');

            reserveBook(bookId, borrowDate, returnDate);
        }

        function reserveBook(bookId, borrowDate, returnDate) {
            console.log('Reserving book with ID:', bookId, 'Borrow Date:', borrowDate, 'Return Date:', returnDate);

            const reserveBtn = $(`[data-toggle="modal"][onclick="openReserveModal(${bookId})"]`);
            const originalText = reserveBtn.html();
            reserveBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...');
            reserveBtn.prop('disabled', true);

            $.ajax({
                url: '/book/reserve/' + bookId,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                    borrow_date: borrowDate,
                    return_date: returnDate
                }),
                xhrFields: {
                    withCredentials: true
                },
                success: function (data) {
                    console.log('Response data:', data);

                    if (data && data.success) {
                        showNotification(data.message || 'Đặt sách thành công!', 'success');

                        reserveBtn.html('<i class="fas fa-check mr-2"></i>Đã đặt sách');
                        reserveBtn.removeClass('btn-success').addClass('btn-secondary disabled');
                        reserveBtn.removeAttr('data-toggle onclick');

                        if (data.data) {
                            console.log('Borrow details:', data.data);
                        }
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi đặt sách!', 'error');

                        reserveBtn.html(originalText);
                        reserveBtn.prop('disabled', false);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    console.log('Response status:', xhr.status);
                    console.log('Response text:', xhr.responseText);

                    let errorMessage = 'Có lỗi xảy ra khi gửi yêu cầu!';
                    if (xhr.responseText) {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            errorMessage = 'Lỗi xử lý dữ liệu từ server: ' + xhr.responseText;
                        }
                    } else if (error.includes('JSON')) {
                        errorMessage = 'Lỗi xử lý dữ liệu từ server. Vui lòng thử lại.';
                    } else if (xhr.status >= 500) {
                        errorMessage = 'Lỗi server. Vui lòng thử lại sau.';
                    } else if (xhr.status === 0) {
                        errorMessage = 'Không thể kết nối đến server. Vui lòng kiểm tra mạng.';
                    }

                    showNotification(errorMessage, 'error');

                    reserveBtn.html(originalText);
                    reserveBtn.prop('disabled', false);
                }
            });
        }
        // Hàm hiển thị thông báo
        function showNotification(message, type = 'info') {
            // Tạo element thông báo
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

            // Styling dựa trên type
            switch (type) {
                case 'success':
                    notification.className += ' bg-green-500 text-white';
                    break;
                case 'error':
                    notification.className += ' bg-red-500 text-white';
                    break;
                case 'warning':
                    notification.className += ' bg-yellow-500 text-white';
                    break;
                default:
                    notification.className += ' bg-blue-500 text-white';
            }

            notification.innerHTML = `
                            <div class="flex items-center">
                                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                                <span>${message}</span>
                                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;

            document.body.appendChild(notification);

            // Animation hiện thông báo
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Hàm debug để kiểm tra response
        function debugResponse(response) {
            console.log('=== DEBUG RESPONSE ===');
            console.log('Status:', response.status);
            console.log('Status Text:', response.statusText);
            console.log('Headers:');
            for (let [key, value] of response.headers.entries()) {
                console.log(`  ${key}: ${value}`);
            }

            return response.clone().text().then(text => {
                console.log('Raw Response Body:', text);
                return response;
            });
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notificationText');

            notificationText.textContent = message;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Add hover effect to book cover
        document.addEventListener('DOMContentLoaded', function () {
            const bookCover = document.getElementById('bookCover');
            if (bookCover) {
                bookCover.addEventListener('click', function () {
                    this.style.transform = 'rotateY(180deg)';
                    setTimeout(() => {
                        this.style.transform = 'rotateY(0deg)';
                    }, 600);
                });
            }

            // Initialize book card animations
            const bookCards = document.querySelectorAll('.book-card');
            bookCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endsection