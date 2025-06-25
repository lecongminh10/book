<?php

namespace Lecon\Mvcoop\Controllers\Client;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Profile;

class ProfileController extends Controller
{
    private Profile $profile;

    public function __construct()
    {
        $this->profile = new Profile();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $this->profile->getUserById($_SESSION['user']['id']);
        $borrowHistory = $this->profile->getBorrowHistory($_SESSION['user']['id']);
        $borrowingStats = $this->profile->getBorrowingStats($_SESSION['user']['id']);

        return $this->renderViewClient('profile', [
            'user' => $user,
            'borrowHistory' => $borrowHistory,
            'borrowingStats' => $borrowingStats
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
}