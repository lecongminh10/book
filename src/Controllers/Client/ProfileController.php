<?php

namespace Lecon\Mvcoop\Controllers\Client;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\BookBorrowing;
use Lecon\Mvcoop\Models\BookRating;
use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\Profile;
use Lecon\Mvcoop\Models\WebSetting;

class ProfileController extends Controller
{
    private Profile $profile;
    private $bookBorrowing;
    private $bookRating;
    private Category $category;

    public function __construct()
    {
        $this->profile = new Profile();
        $this->bookBorrowing = new BookBorrowing();
        $this->bookRating= new BookRating();
        $this->category = new Category;
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $webSetting = new WebSetting();
        $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
        $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        // Lấy danh sách tất cả các category
        $categories = $this->category->getAll();

        $user = $this->profile->getUserById($_SESSION['user']['id']);
        $borrowHistory = $this->profile->getBorrowHistory($_SESSION['user']['id']);
        $borrowingStats = $this->profile->getBorrowingStats($_SESSION['user']['id']);

        return $this->renderViewClient('profile', [
            'user' => $user,
            'borrowHistory' => $borrowHistory,
            'borrowingStats' => $borrowingStats,
            'categories' => $categories,
            'logo' => $logo,
            'slide_1' => $slide_1,
            'footer' => $footer,
            'hotline' => $hotline,
            'title_logo' => $title_logo,
        ]);
    }

    public function update()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            $data = [
                'student_id' => $_POST['student_id'] ?? '',
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'full_name' => $_POST['full_name'] ?? ''
            ];

            // Validate data
            $errors = $this->validateUserData($data, $userId);

            if (empty($errors)) {
                if ($this->profile->updateUser($userId, $data)) {
                    // Update session data
                    $_SESSION['user'] = array_merge($_SESSION['user'], $data);
                    $_SESSION['success'] = 'Cập nhật thông tin thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin!';
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['active_tab'] = 'profile-info'; // Giữ nguyên tab thông tin cá nhân
            }

            header('Location: /profile');
            exit;
        }
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate password change
            $errors = $this->validatePasswordChange($userId, $currentPassword, $newPassword, $confirmPassword);

            if (empty($errors)) {
                // Sử dụng MD5 để mã hóa mật khẩu (theo yêu cầu hiện tại của bạn)
                $hashedPassword = md5($newPassword);
                if ($this->profile->updatePassword($userId, $hashedPassword)) {
                    $_SESSION['success'] = 'Đổi mật khẩu thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi đổi mật khẩu!';
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['active_tab'] = 'change-password'; // Giữ nguyên tab đổi mật khẩu
            }

            header('Location: /profile');
            exit;
        }
    }

    private function validateUserData($data, $userId)
    {
        $errors = [];

        // Validate student_id if provided
        if (!empty($data['student_id'])) {
            // Check if student_id exists (except current user)
            if ($this->profile->isStudentIdExists($data['student_id'], $userId)) {
                $errors['student_id'] = 'Mã sinh viên đã tồn tại';
            }
        }

        // Validate required fields
        if (empty($data['username'])) {
            $errors['username'] = 'Tên đăng nhập không được để trống';
        } else {
            // Check if username exists (except current user)
            if ($this->profile->isUsernameExists($data['username'], $userId)) {
                $errors['username'] = 'Tên đăng nhập đã tồn tại';
            }
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email không được để trống';
        } else {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email không hợp lệ';
            } else {
                // Check if email exists (except current user)
                if ($this->profile->isEmailExists($data['email'], $userId)) {
                    $errors['email'] = 'Email đã tồn tại';
                }
            }
        }

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Họ tên không được để trống';
        }

        return $errors;
    }

    private function validatePasswordChange($userId, $currentPassword, $newPassword, $confirmPassword)
    {
        $errors = [];

        // Get current password from database
        $userPassword = $this->profile->getUserPassword($userId);

        // Kiểm tra mật khẩu hiện tại bằng MD5
        if (md5($currentPassword) !== $userPassword) {
            $errors['current_password'] = 'Mật khẩu hiện tại không đúng';
        }

        if (strlen($newPassword) < 6) {
            $errors['new_password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }

        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Xác nhận mật khẩu không khớp';
        }

        return $errors;
    }
    
    public function saveRating() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
            exit;
        }
        header('Content-Type: application/json');
        try {
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['user']['id'])) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá.']);
                exit;
            }
    
            // Chỉ cho phép POST method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
                exit;
            }
    
            // Đọc và giải mã dữ liệu JSON từ request body
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
    
            // Lấy giá trị từ $data
            $borrowId = isset($data['borrow_id']) ? (int)$data['borrow_id'] : 0;
            $rating = isset($data['rating']) ? (int)$data['rating'] : 0;
            $comment = isset($data['comment']) ? trim($data['comment']) : '';
    
            // Ghi log để debug (tùy chọn)
            error_log("Received JSON - borrow_id: $borrowId, rating: $rating, comment: $comment");
    
            // Validate dữ liệu
            if ($borrowId <= 0) {
                echo json_encode(['success' => false, 'message' => 'Phiếu mượn không hợp lệ.']);
                exit;
            }
    
            if ($rating < 1 || $rating > 5) {
                echo json_encode(['success' => false, 'message' => 'Điểm đánh giá phải từ 1 đến 5 sao.']);
                exit;
            }
    
            if (empty($comment)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập nhận xét.']);
                exit;
            }
    
            if (strlen($comment) > 500) {
                echo json_encode(['success' => false, 'message' => 'Nhận xét quá dài (tối đa 500 ký tự).']);
                exit;
            }
    
            $userId = $_SESSION['user']['id'];
    
            // Kiểm tra phiếu mượn hợp lệ và đã trả sách
            $borrow = $this->bookBorrowing->hasReturnBorrowing($userId, $borrowId);
            if (!$borrow) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu mượn hoặc sách chưa được trả.']);
                exit;
            }
    
            $bookId = $borrow['book_id'];
    
            // Kiểm tra đã có đánh giá chưa
            $existingRating = $this->bookRating->hasBookRating($userId, $bookId);
            if ($existingRating) {
                $success = $this->bookRating->updateRating($existingRating['id'], $rating, $comment);
                $message = $success ? 'Đánh giá đã được cập nhật thành công.' : 'Không thể cập nhật đánh giá. Vui lòng thử lại.';
            } else {
                $success = $this->bookRating->createRating($userId, $bookId, $rating, $comment);
                $message = $success ? 'Đánh giá đã được lưu thành công.' : 'Không thể lưu đánh giá. Vui lòng thử lại.';
            }
    
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
    
        } catch (\Exception $e) {
            error_log("Error in saveRating: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi. Vui lòng thử lại.']);
            exit;
        }
    }
    

    public function getRating() {
        header('Content-Type: application/json; charset=UTF-8');
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để xem đánh giá.']);
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
                exit;
            }
            $userId = $_SESSION['user']['id'];
            $borrowId = isset($_GET['borrow_id']) ? (int)$_GET['borrow_id'] : 0;
            if ($borrowId <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID phiếu mượn không hợp lệ.']);
                exit;
            }
            $borrow = $this->bookBorrowing->hasReturnBorrowing($userId, $borrowId);
            if (!$borrow) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu mượn hoặc sách chưa được trả.']);
                exit;
            }
            $bookId = $borrow['book_id'];
            $rating = $this->bookRating->getRatingByUserAndBook($userId, $bookId);
            if ($rating) {
                echo json_encode(['success' => true, 'message' => 'Lấy đánh giá thành công', 'rating' => $rating]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Chưa có đánh giá']);
                exit;
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi lấy đánh giá: ' . $e->getMessage()]);
            exit;
        }
    }
}