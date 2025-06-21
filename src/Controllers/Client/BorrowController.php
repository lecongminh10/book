<?php

namespace Lecon\Mvcoop\Controllers\Client;

use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\BookBorrowing;

class BorrowController {
    private $bookModel;
    private $borrowingModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->borrowingModel = new BookBorrowing();
    }

    public function borrowBook() {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = $_POST['book_id'] ?? null;
            $borrowDate = $_POST['borrow_date'] ?? null;
            $returnDate = $_POST['return_date'] ?? null;

            if (!$bookId || !$borrowDate || !$returnDate) {
                $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin";
                header('Location: /book/' . $bookId);
                exit();
            }

            // Kiểm tra sách có khả dụng không
            if (!$this->bookModel->isBookAvailable($bookId)) {
                $_SESSION['error'] = "Sách này hiện không khả dụng";
                header('Location: /book/' . $bookId);
                exit();
            }

            // Tạo yêu cầu mượn sách
            $result = $this->borrowingModel->createBorrowing(
                $_SESSION['user']['id'],
                $bookId,
                $borrowDate,
                $returnDate
            );

            if ($result) {
                $_SESSION['success'] = "Yêu cầu mượn sách đã được gửi thành công";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
            }

            header('Location: /book/' . $bookId);
            exit();
        }
    }

    public function myBorrowings() {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit();
        }

        $borrowings = $this->borrowingModel->getUserBorrowings($_SESSION['user']['id']);
        
        require_once __DIR__ . '/../../Views/client/borrowings/index.php';
    }

    public function cancelBorrowing($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit();
        }

        $borrowing = $this->borrowingModel->getBorrowingById($id);
        
        if (!$borrowing || $borrowing['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Không tìm thấy yêu cầu mượn sách";
            header('Location: /my-borrowings');
            exit();
        }

        if ($borrowing['status'] !== 'pending') {
            $_SESSION['error'] = "Không thể hủy yêu cầu đã được xử lý";
            header('Location: /my-borrowings');
            exit();
        }

        $result = $this->borrowingModel->updateBorrowingStatus($id, 'rejected');
        
        if ($result) {
            $_SESSION['success'] = "Đã hủy yêu cầu mượn sách";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
        }

        header('Location: /my-borrowings');
        exit();
    }
} 