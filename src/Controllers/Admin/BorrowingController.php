<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Models\BookBorrowing;
use Lecon\Mvcoop\Commons\Controller;

class BorrowingController extends Controller {
    private $borrowingModel;
    private string $folder = 'borrowings.';
    public function __construct() {
        $this->borrowingModel = new BookBorrowing();
    }

    public function index() {
        $data = $this->borrowingModel->getAllBorrowings();
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    public function pending() {
        $borrowings = $this->borrowingModel->getPendingBorrowings();
        require_once __DIR__ . '/../../Views/admin/borrowings/pending.php';
    }

    public function active() {
        $borrowings = $this->borrowingModel->getActiveBorrowings();
        require_once __DIR__ . '/../../Views/admin/borrowings/active.php';
    }

    public function overdue() {
        $borrowings = $this->borrowingModel->getOverdueBorrowings();
        require_once __DIR__ . '/../../Views/admin/borrowings/overdue.php';
    }

    public function approve($id) {
        $borrowing = $this->borrowingModel->getBorrowingById($id);
        
        if (!$borrowing) {
            $_SESSION['error'] = "Không tìm thấy yêu cầu mượn sách";
            header('Location: /admin/borrowings');
            exit();
        }

        if ($borrowing['status'] !== 'pending') {
            $_SESSION['error'] = "Yêu cầu này đã được xử lý";
            header('Location: /admin/borrowings');
            exit();
        }

        $result = $this->borrowingModel->updateBorrowingStatus($id, 'approved');
        
        if ($result) {
            $_SESSION['success'] = "Đã duyệt yêu cầu mượn sách";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
        }

        header('Location: /admin/borrowings');
        exit();
    }

    public function reject($id) {
        $borrowing = $this->borrowingModel->getBorrowingById($id);
        
        if (!$borrowing) {
            $_SESSION['error'] = "Không tìm thấy yêu cầu mượn sách";
            header('Location: /admin/borrowings');
            exit();
        }

        if ($borrowing['status'] !== 'pending') {
            $_SESSION['error'] = "Yêu cầu này đã được xử lý";
            header('Location: /admin/borrowings');
            exit();
        }

        $result = $this->borrowingModel->updateBorrowingStatus($id, 'rejected');
        
        if ($result) {
            $_SESSION['success'] = "Đã từ chối yêu cầu mượn sách";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
        }

        header('Location: /admin/borrowings');
        exit();
    }

    public function return($id) {
        $borrowing = $this->borrowingModel->getBorrowingById($id);
        
        if (!$borrowing) {
            $_SESSION['error'] = "Không tìm thấy yêu cầu mượn sách";
            header('Location: /admin/borrowings');
            exit();
        }

        if ($borrowing['status'] !== 'approved') {
            $_SESSION['error'] = "Sách này chưa được mượn";
            header('Location: /admin/borrowings');
            exit();
        }

        $result = $this->borrowingModel->updateBorrowingStatus($id, 'returned');
        
        if ($result) {
            $_SESSION['success'] = "Đã cập nhật trạng thái trả sách";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
        }

        header('Location: /admin/borrowings');
        exit();
    }

    public function statistics() {
        $stats = $this->borrowingModel->getBorrowingStatistics();
        require_once __DIR__ . '/../../Views/admin/borrowings/statistics.php';
    }
} 