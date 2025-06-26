@extends('layouts.master')

@section('title', 'Hồ sơ cá nhân')

@section('content')
    <div class="container mt-4" style="margin-bottom: 80px">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Hồ sơ cá nhân</h2>

                <!-- Alert Messages -->
                @if (isset($_SESSION['success']))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $_SESSION['success'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @php unset($_SESSION['success']); @endphp
                @endif

                @if (isset($_SESSION['error']))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $_SESSION['error'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @php unset($_SESSION['error']); @endphp
                @endif

                <!-- Profile Tabs -->
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if (!isset($_SESSION['active_tab']) || $_SESSION['active_tab'] === 'profile-info') active @endif" id="profile-info-tab"
                            data-bs-toggle="tab" data-bs-target="#profile-info" type="button" role="tab">
                            <i class="fas fa-user"></i> Thông tin cá nhân
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'borrow-history') active @endif" id="borrow-history-tab"
                            data-bs-toggle="tab" data-bs-target="#borrow-history" type="button" role="tab">
                            <i class="fas fa-history"></i> Lịch sử mượn sách
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'change-password') active @endif" id="change-password-tab"
                            data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab">
                            <i class="fas fa-lock"></i> Đổi mật khẩu
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="profileTabsContent">
                    <!-- Profile Information Tab -->
                    <div class="tab-pane fade @if (!isset($_SESSION['active_tab']) || $_SESSION['active_tab'] === 'profile-info') show active @endif" id="profile-info"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Cập nhật thông tin cá nhân</h5>
                            </div>
                            <div class="card-body">
                                <form action="/profile/update" method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="student_id" class="form-label">Mã sinh viên</label>
                                                <input type="text"
                                                    class="form-control @if (isset($_SESSION['errors']['student_id'])) is-invalid @endif"
                                                    id="student_id" name="student_id"
                                                    value="{{ $user['student_id'] ?? '' }}">
                                                @if (isset($_SESSION['errors']['student_id']))
                                                    <div class="invalid-feedback">{{ $_SESSION['errors']['student_id'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Tên đăng nhập *</label>
                                                <input type="text"
                                                    class="form-control @if (isset($_SESSION['errors']['username'])) is-invalid @endif"
                                                    id="username" name="username" value="{{ $user['username'] }}" required>
                                                @if (isset($_SESSION['errors']['username']))
                                                    <div class="invalid-feedback">{{ $_SESSION['errors']['username'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="email"
                                                    class="form-control @if (isset($_SESSION['errors']['email'])) is-invalid @endif"
                                                    id="email" name="email" value="{{ $user['email'] }}" required>
                                                @if (isset($_SESSION['errors']['email']))
                                                    <div class="invalid-feedback">{{ $_SESSION['errors']['email'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="full_name" class="form-label">Họ tên *</label>
                                                <input type="text"
                                                    class="form-control @if (isset($_SESSION['errors']['full_name'])) is-invalid @endif"
                                                    id="full_name" name="full_name" value="{{ $user['full_name'] }}"
                                                    required>
                                                @if (isset($_SESSION['errors']['full_name']))
                                                    <div class="invalid-feedback">{{ $_SESSION['errors']['full_name'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Cập nhật thông tin
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Borrow History Tab -->
                    <div class="tab-pane fade @if (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'borrow-history') show active @endif" id="borrow-history"
                        role="tabpanel" style="margin-bottom: 100px">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Lịch sử mượn sách</h5>
                            </div>
                            <div class="card-body">
                                @if (empty($borrowHistory))
                                    <div class="text-center py-4">
                                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Bạn chưa có lịch sử mượn sách nào.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Tên sách</th>
                                                    <th>Tác giả</th>
                                                    <th>ISBN</th>
                                                    <th>Ngày mượn</th>
                                                    <th>Ngày trả</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ngày đăng ký</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($borrowHistory as $index => $borrow)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $borrow['title'] }}</td>
                                                        <td>{{ $borrow['author'] }}</td>
                                                        <td>{{ $borrow['isbn'] }}</td>
                                                        <td>{{ date('d/m/Y', strtotime($borrow['borrow_date'])) }}</td>
                                                        <td>{{ date('d/m/Y', strtotime($borrow['return_date'])) }}</td>
                                                        <td>
                                                            @php
                                                                $statusClass = [
                                                                    'pending' => 'warning',
                                                                    'approved' => 'success',
                                                                    'rejected' => 'danger',
                                                                    'returned' => 'info',
                                                                ];
                                                                $statusText = [
                                                                    'pending' => 'Chờ duyệt',
                                                                    'approved' => 'Đã duyệt',
                                                                    'rejected' => 'Từ chối',
                                                                    'returned' => 'Đã trả',
                                                                ];
                                                            @endphp
                                                            <span class="badge bg-{{ $statusClass[$borrow['status']] }}">
                                                                {{ $statusText[$borrow['status']] }}
                                                            </span>
                                                            @if ($borrow['status'] == 'returned')
                                                                @php
                                                                    $hasRating = isset($borrow['rating']) && $borrow['rating'];
                                                                @endphp
                                                                @if ($hasRating)
                                                                    <button class="btn btn-sm btn-success ms-2 view-rating-btn"
                                                                        data-id="{{ $borrow['id'] }}"
                                                                        data-book-title="{{ $borrow['title'] }}">
                                                                        <i class="fas fa-eye"></i> Xem đánh giá
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-sm btn-warning ms-2 rating-btn"
                                                                        data-id="{{ $borrow['id'] }}"
                                                                        data-book-title="{{ $borrow['title'] }}">
                                                                        <i class="fas fa-star"></i> Đánh giá
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{ date('d/m/Y H:i', strtotime($borrow['created_at'])) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Tab -->
                    <div class="tab-pane fade @if (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'change-password') show active @endif" id="change-password"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Đổi mật khẩu</h5>
                            </div>
                            <div class="card-body">
                                <form action="/profile/change-password" method="POST">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Mật khẩu hiện tại *</label>
                                        <input type="password"
                                            class="form-control @if (isset($_SESSION['errors']['current_password'])) is-invalid @endif"
                                            id="current_password" name="current_password" required>
                                        @if (isset($_SESSION['errors']['current_password']))
                                            <div class="invalid-feedback">{{ $_SESSION['errors']['current_password'] }}</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Mật khẩu mới *</label>
                                        <input type="password"
                                            class="form-control @if (isset($_SESSION['errors']['new_password'])) is-invalid @endif"
                                            id="new_password" name="new_password" required>
                                        @if (isset($_SESSION['errors']['new_password']))
                                            <div class="invalid-feedback">{{ $_SESSION['errors']['new_password'] }}</div>
                                        @endif
                                        <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới *</label>
                                        <input type="password"
                                            class="form-control @if (isset($_SESSION['errors']['confirm_password'])) is-invalid @endif"
                                            id="confirm_password" name="confirm_password" required>
                                        @if (isset($_SESSION['errors']['confirm_password']))
                                            <div class="invalid-feedback">{{ $_SESSION['errors']['confirm_password'] }}</div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Đổi mật khẩu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Đánh giá sách</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên sách:</label>
                            <p id="bookTitle" class="text-muted"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá sao:</label>
                            <div class="rating-stars" id="starRating">
                                <span class="star" data-rating="1">★</span>
                                <span class="star" data-rating="2">★</span>
                                <span class="star" data-rating="3">★</span>
                                <span class="star" data-rating="4">★</span>
                                <span class="star" data-rating="5">★</span>
                            </div>
                            <small class="text-muted">Nhấp vào sao để đánh giá</small>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold">Nhận xét:</label>
                            <textarea class="form-control" id="comment" rows="4" placeholder="Chia sẻ cảm nhận của bạn về cuốn sách..."></textarea>
                        </div>
                        <input type="hidden" id="borrowId" value="">
                        <input type="hidden" id="selectedRating" value="0">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="submitRating">Gửi đánh giá</button>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .rating-stars .star {
            cursor: pointer;
            color: #ddd; /* Default color for unselected stars */
            font-size: 24px; /* Optional: Adjust star size */
        }
        .rating-stars .star.active {
            color: #f5c518; /* Color for selected stars */
        }
    </style>
    
    <script>
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating')); // Get the clicked star's rating
                document.getElementById('selectedRating').value = rating; // Update hidden input
                // Remove 'active' class from all stars
                document.querySelectorAll('.star').forEach(s => s.classList.remove('active'));
                // Add 'active' class to all stars up to the selected rating
                document.querySelectorAll('.star').forEach(s => {
                    if (parseInt(s.getAttribute('data-rating')) <= rating) {
                        s.classList.add('active');
                    }
                });
            });
        });
    </script>
    <!-- View Rating Modal -->
    <div class="modal fade" id="viewRatingModal" tabindex="-1" aria-labelledby="viewRatingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRatingModalLabel">Đánh giá của bạn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sách:</label>
                        <p id="viewBookTitle" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Đánh giá:</label>
                        <div id="viewStarRating" class="rating-display"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nhận xét:</label>
                        <p id="viewComment" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ngày đánh giá:</label>
                        <p id="viewRatingDate" class="text-muted"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-warning" id="editRating">Chỉnh sửa đánh giá</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries and Custom Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Event handler cho nút "Đánh giá"
            $(document).on('click', '.rating-btn', function () {
                console.log('Rating button clicked');
                const borrowId = $(this).data('id');
                const bookTitle = $(this).data('book-title');
                openRatingModal(borrowId, bookTitle);
            });

            // Event handler cho nút "Xem đánh giá"
            $(document).on('click', '.view-rating-btn', function () {
                console.log('View rating button clicked');
                const borrowId = $(this).data('id');
                const bookTitle = $(this).data('book-title');
                openViewRatingModal(borrowId, bookTitle);
            });

            // Initialize star rating interaction
            $('.star').on('click', function () {
                console.log('Star clicked');
                const rating = $(this).data('rating');
                $('#selectedRating').val(rating);
                updateStarDisplay(rating);
            });

            $('.star').on('mouseenter', function () {
                const rating = $(this).data('rating');
                updateStarDisplay(rating);
            });

            $('.star').on('mouseleave', function () {
                const selectedRating = $('#selectedRating').val();
                updateStarDisplay(selectedRating);
            });

            // Update star display based on rating
            function updateStarDisplay(rating) {
                $('.star').each(function () {
                    const starRating = $(this).data('rating');
                    if (starRating <= rating) {
                        $(this).find('i').addClass('text-warning').removeClass('text-muted');
                    } else {
                        $(this).find('i').addClass('text-muted').removeClass('text-warning');
                    }
                });
            }

            $('#submitRating').on('click', function () {
                    const borrowId = $('#borrowId').val();
                    const rating = $('#selectedRating').val();
                    const comment = $('#comment').val().trim();

                    if (!borrowId || borrowId <= 0) {
                        showNotification('Vui lòng chọn phiếu mượn hợp lệ.', 'error');
                        return;
                    }
                    if (!rating || rating < 1 || rating > 5) {
                        showNotification('Vui lòng chọn điểm đánh giá từ 1 đến 5 sao.', 'error');
                        return;
                    }
                    if (!comment || comment.length > 500) {
                        showNotification('Nhận xét không hợp lệ hoặc quá dài (tối đa 500 ký tự).', 'error');
                        return;
                    }

                    $.ajax({
                        url: '/profile/save-rating',
                        type: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify({
                            borrow_id: parseInt(borrowId),
                            rating: parseInt(rating),
                            comment: comment
                        }),
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function (data) {
                            console.log('Response data:', data);
                            if (data.success) {
                                $('#ratingModal').modal('hide');
                                showNotification(data.message, 'success');
                            } else {
                                showNotification(data.message, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                        }
                    });
                });

            function openRatingModal(borrowId, bookTitle) {
                console.log('Opening rating modal:', { borrowId, bookTitle });
                $('#borrowId').val(borrowId);
                $('#bookTitle').text(bookTitle);
                $('#selectedRating').val(0);
                $('#comment').val('');
                updateStarDisplay(0);
                $('#ratingModal').modal('show');
                getRating(borrowId);
            }

            // Open view rating modal
            function openViewRatingModal(borrowId, bookTitle) {
                console.log('Opening view rating modal:', { borrowId, bookTitle });
                $('#viewBookTitle').text(bookTitle);
                $('#viewStarRating').empty();
                $('#viewComment').text('');
                $('#viewRatingDate').text('');
                $('#viewRatingModal').modal('show');
                getRating(borrowId, true);
            }

            // Edit rating from view modal
            $('#editRating').on('click', function () {
                console.log('Edit rating clicked');
                const borrowId = $('#borrowId').val();
                const bookTitle = $('#viewBookTitle').text();
                $('#viewRatingModal').modal('hide');
                setTimeout(function () {
                    openRatingModal(borrowId, bookTitle);
                }, 300);
            });

            // Fetch rating
            function getRating(borrowId, forViewModal = false) {
                console.log('Fetching rating for borrowId:', borrowId, 'forViewModal:', forViewModal);
                $.ajax({
                    url: '/profile/get-rating?borrow_id=' + borrowId,
                    type: 'GET',
                    dataType: 'json',
                    xhrFields: { withCredentials: true },
                    success: function (data) {
                        console.log('Get rating response:', data);
                        if (data.success && data.rating) {
                            if (forViewModal) {
                                $('#viewStarRating').html(generateStarDisplay(data.rating.rating));
                                $('#viewComment').text(data.rating.comment);
                                const date = data.rating.updated_at || data.rating.created_at;
                                $('#viewRatingDate').text(new Date(date).toLocaleDateString('vi-VN'));
                                $('#borrowId').val(borrowId);
                            } else {
                                $('#selectedRating').val(data.rating.rating);
                                $('#comment').val(data.rating.comment);
                                updateStarDisplay(data.rating.rating);
                            }
                        } else {
                            if (forViewModal) {
                                $('#viewStarRating').html('Chưa có đánh giá.');
                                $('#viewComment').text('Chưa có nhận xét.');
                                $('#viewRatingDate').text('-');
                            }
                            if (data.message && !data.message.includes('không tìm thấy')) {
                                showNotification(data.message, 'error');
                            }
                        }
                    },
                    error: function (xhr) {
                        console.error('Get rating error:', xhr.responseJSON);
                        if (forViewModal) {
                            $('#viewStarRating').html('Chưa có đánh giá.');
                            $('#viewComment').text('Chưa có nhận xét.');
                            $('#viewRatingDate').text('-');
                        }
                        let errorMessage = xhr.responseJSON?.message || 'Có lỗi khi lấy đánh giá.';
                        showNotification(errorMessage, 'error');
                    }
                });
            }

            // Generate star display for view modal
            function generateStarDisplay(rating) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<i class="fas fa-star ${i <= rating ? 'text-warning' : 'text-muted'}"></i>`;
                }
                return stars;
            }

            // CSS cho notification
            if (!$('style#notification-style').length) {
                $('head').append(`
                    <style id="notification-style">
                        .notification {
                            position: fixed;
                            top: 20px;
                            right: 20px;
                            padding: 15px 20px;
                            border-radius: 5px;
                            color: white;
                            font-weight: bold;
                            z-index: 9999;
                            opacity: 0;
                            transform: translateX(100%);
                            transition: all 0.3s ease;
                        }
                        .notification.show {
                            opacity: 1;
                            transform: translateX(0);
                        }
                        .notification.success {
                            background-color: #28a745;
                        }
                        .notification.error {
                            background-color: #dc3545;
                        }
                    </style>
                `);
            }

            // Notification function
            function showNotification(message, type) {
                const notification = $('<div>', {
                    class: `notification ${type === 'success' ? 'success' : 'error'}`,
                    text: message
                });
                $('body').append(notification);
                setTimeout(() => notification.addClass('show'), 100);
                setTimeout(() => {
                    notification.removeClass('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        @php
            // Clear errors and active tab after displaying
            if (isset($_SESSION['errors'])) {
                unset($_SESSION['errors']);
            }
            if (isset($_SESSION['active_tab'])) {
                unset($_SESSION['active_tab']);
            }
        @endphp
    </script>
@endsection
