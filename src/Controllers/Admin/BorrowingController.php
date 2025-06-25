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
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $borrowings = $this->borrowingModel->getBorrowingsPaginated(null, $limit, $offset);
        $total = $this->borrowingModel->countBorrowings();
        $stats = $this->borrowingModel->getBorrowingStatistics();
        $totalPages = ceil($total / $limit);
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            [
                'borrowings' => $borrowings,
                'stats' => $stats,
                'page' => $page,
                'totalPages' => $totalPages,
                'baseUrl' => '/admin/borrowings'
            ]
        );
    }

    public function pending() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $borrowings = $this->borrowingModel->getBorrowingsPaginated('pending', $limit, $offset);
        $total = $this->borrowingModel->countBorrowings('pending');
        $stats = $this->borrowingModel->getBorrowingStatistics();
        $totalPages = ceil($total / $limit);
        return $this->renderViewAdmin(
            $this->folder . 'index',
            [
                'borrowings' => $borrowings,
                'stats' => $stats,
                'page' => $page,
                'totalPages' => $totalPages,
                'baseUrl' => '/admin/borrowings/pending'
            ]
        );
    }

    public function active() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $borrowings = $this->borrowingModel->getBorrowingsPaginated('approved', $limit, $offset);
        $total = $this->borrowingModel->countBorrowings('approved');
        $stats = $this->borrowingModel->getBorrowingStatistics();
        $totalPages = ceil($total / $limit);
        return $this->renderViewAdmin(
            $this->folder . 'index',
            [
                'borrowings' => $borrowings,
                'stats' => $stats,
                'page' => $page,
                'totalPages' => $totalPages,
                'baseUrl' => '/admin/borrowings/active'
            ]
        );
    }

    public function overdue() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        // Quá hạn là approved và return_date < hôm nay
        $borrowings = $this->borrowingModel->getBorrowingsPaginated('approved', $limit, $offset);
        // Lọc lại trong PHP nếu cần, hoặc viết hàm riêng nếu muốn tối ưu hơn
        $borrowings = array_filter($borrowings, function($b) {
            return strtotime($b['return_date']) < strtotime(date('Y-m-d'));
        });
        $total = count($borrowings);
        $stats = $this->borrowingModel->getBorrowingStatistics();
        $totalPages = ceil($total / $limit);
        // Lấy đúng page
        $borrowings = array_slice($borrowings, 0, $limit);
        return $this->renderViewAdmin(
            $this->folder . 'index',
            [
                'borrowings' => $borrowings,
                'stats' => $stats,
                'page' => $page,
                'totalPages' => $totalPages,
                'baseUrl' => '/admin/borrowings/overdue'
            ]
        );
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

    public function returned() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $borrowings = $this->borrowingModel->getBorrowingsPaginated('returned', $limit, $offset);
        $total = $this->borrowingModel->countBorrowings('returned');
        $stats = $this->borrowingModel->getBorrowingStatistics();
        $totalPages = ceil($total / $limit);
        return $this->renderViewAdmin(
            $this->folder . 'index',
            [
                'borrowings' => $borrowings,
                'stats' => $stats,
                'page' => $page,
                'totalPages' => $totalPages,
                'baseUrl' => '/admin/borrowings/returned'
            ]
        );
    }

    public function rejected() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $borrowings = $this->borrowingModel->getBorrowingsPaginated('rejected', $limit, $offset);
        $total = $this->borrowingModel->countBorrowings('rejected');
        $stats = $this->borrowingModel->getBorrowingStatistics();
        $totalPages = ceil($total / $limit);
        return $this->renderViewAdmin(
            $this->folder . 'index',
            [
                'borrowings' => $borrowings,
                'stats' => $stats,
                'page' => $page,
                'totalPages' => $totalPages,
                'baseUrl' => '/admin/borrowings/rejected'
            ]
        );
    }

    public function statistics() {
        $stats = $this->borrowingModel->getBorrowingStatistics();
        require_once __DIR__ . '/../../Views/admin/borrowings/statistics.php';
    }
} 