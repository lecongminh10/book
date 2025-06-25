<?php

namespace Lecon\Mvcoop\Controllers\Client;

use DateTime;
use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\User;
use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\WebSetting;
use Lecon\Mvcoop\Models\Post;
use Lecon\Mvcoop\Models\BookBorrowing;

class BookDetailController extends Controller {
    private $bookModel;
    private $categoryModel;
    private $webSettingModel;
    private $bookBorrowingModel;
    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
        $this->webSettingModel = new WebSetting();
        $this->bookBorrowingModel = new BookBorrowing();
    }

    public function index($id) {
        try {
            // Lấy thông tin chi tiết sách
            $book = $this->bookModel->getBookById($id);
            
            if (!$book) {
                // Chuyển hướng về trang 404 nếu không tìm thấy sách
                header("HTTP/1.0 404 Not Found");
                $this->renderViewClient('errors.404');
                return;
            }

            // Lấy các sách liên quan cùng thể loại
            $relatedBooks = [];
            if ($book['category_id']) {
                $allBooksInCategory = $this->bookModel->getBooksByCategory($book['category_id']);
                // Loại bỏ sách hiện tại khỏi danh sách liên quan
                $relatedBooks = array_filter($allBooksInCategory, function($relatedBook) use ($id) {
                    return $relatedBook['id'] != $id;
                });
                // Chỉ lấy tối đa 6 sách liên quan
                $relatedBooks = array_slice($relatedBooks, 0, 6);
            }
            $webSetting = new WebSetting();
            $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
            $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
            $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
            $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
            $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
            $categories = $this->categoryModel->getAll();

            // Truyền dữ liệu vào view
            $data = [
                'book' => $book,
                'relatedBooks' => $relatedBooks,
                'categories' => $categories,
                'logo' => $logo,
                'slide_1' => $slide_1,
                'footer' => $footer,
                'hotline' => $hotline,
                'title_logo' => $title_logo,
                'pageTitle' => $book['title'] . ' - Chi tiết sách'
            ];
            $this->renderViewClient('book-detail', $data);

        } catch (\Exception $e) {
            // Xử lý lỗi
            error_log("Error in BookDetailController: " . $e->getMessage());
            header("HTTP/1.0 500 Internal Server Error");
            $this->renderViewClient('errors.500');
        }
    }

    public function read($id) {
        try {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (!isset($_SESSION['user'])) {
                $_SESSION['error'] = 'Vui lòng đăng nhập để đọc sách online.';
                header("Location: /login");
                return;
            }
    
            // Lấy thông tin sách để đọc online
            $book = $this->bookModel->getBookById($id);
            
            if (!$book) {
                header("HTTP/1.0 404 Not Found");
                $this->renderViewClient('errors.404');
                return;
            }
    
            // Kiểm tra xem sách có nội dung để đọc không
            if (empty($book['content'])) {
                $_SESSION['error'] = 'Sách này chưa có nội dung để đọc online.';
                header("Location: /book-detail/$id");
                return;
            }
    
            $webSetting = new WebSetting();
            $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
            $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
            $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
            $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
            $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
            $categories = $this->categoryModel->getAll();
            $data = [
                'book' => $book,
                'categories' => $categories,
                'logo' => $logo,
                'slide_1' => $slide_1,
                'footer' => $footer,
                'hotline' => $hotline,
                'title_logo' => $title_logo,
                'pageTitle' => 'Đọc online: ' . $book['title']
            ];
            $this->renderViewClient('book-read', $data);
    
        } catch (\Exception $e) {
            error_log("Error in BookDetailController read: " . $e->getMessage());
            header("HTTP/1.0 500 Internal Server Error");
            $this->renderViewClient('errors.500');
        }
    }

    public function reserve($bookId) {
        // Đảm bảo chỉ xử lý request POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
            exit;
        }
    
        // Set header JSON ngay từ đầu
        header('Content-Type: application/json');
    
        try {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (!isset($_SESSION['user'])) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt sách.']);
                exit;
            }
    
            // Lấy user_id từ session
            $userId = $_SESSION['user']['id'];
    
            // Đọc dữ liệu JSON từ request body
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
    
            // Kiểm tra dữ liệu đầu vào
            if (!$data || !isset($data['borrow_date']) || !isset($data['return_date'])) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ. Vui lòng chọn ngày mượn và ngày trả.']);
                exit;
            }
    
            $borrowDate = $data['borrow_date'];
            $returnDate = $data['return_date'];
    
            // Validate định dạng ngày
            if (!DateTime::createFromFormat('Y-m-d', $borrowDate) || !DateTime::createFromFormat('Y-m-d', $returnDate)) {
                echo json_encode(['success' => false, 'message' => 'Định dạng ngày không hợp lệ.']);
                exit;
            }
    
            // Validate logic ngày
            if (strtotime($returnDate) <= strtotime($borrowDate)) {
                echo json_encode(['success' => false, 'message' => 'Ngày trả phải sau ngày mượn.']);
                exit;
            }
    
            // Validate ngày mượn không được trong quá khứ
            if (strtotime($borrowDate) < strtotime(date('Y-m-d'))) {
                echo json_encode(['success' => false, 'message' => 'Ngày mượn không được trong quá khứ.']);
                exit;
            }
    
            // Kiểm tra xem sách có tồn tại không
            $book = $this->bookModel->getBookById($bookId);
            if (!$book) {
                echo json_encode(['success' => false, 'message' => 'Sách không tồn tại.']);
                exit;
            }
    
            // Kiểm tra xem người dùng đã đặt sách này chưa (tránh đặt lại khi đang pending)
            if ($this->bookBorrowingModel->hasPendingBorrowing($userId, $bookId)) {
                echo json_encode(['success' => false, 'message' => 'Bạn đã đặt sách này rồi. Vui lòng chờ phê duyệt.']);
                exit;
            }
    
            // Kiểm tra xem sách có đang được mượn không
            if (!$this->bookModel->isBookAvailable($bookId)) {
                echo json_encode(['success' => false, 'message' => 'Sách đang được mượn. Vui lòng thử lại sau.']);
                exit;
            }
    
            // Lưu vào bảng book_borrowings bằng hàm createBorrowing
            $success = $this->bookBorrowingModel->createBorrowing($userId, $bookId, $borrowDate, $returnDate);
    
            if ($success) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đặt sách thành công! Chúng tôi sẽ liên hệ với bạn sớm.',
                    'data' => [
                        'book_title' => $book['title'],
                        'borrow_date' => $borrowDate,
                        'return_date' => $returnDate
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể đặt sách. Vui lòng thử lại.']);
            }
    
        } catch (\Exception $e) {
            error_log("Error in BookDetailController reserve: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi đặt sách.']);
        }
        
        exit; // Quan trọng: dừng execution để tránh output thêm
    }
    public function list_book_cat($categoryId) {
        try {
            $books = $this->bookModel->list_book_cat($categoryId);
            $cat = $this->categoryModel->getByID($categoryId);
            $name_cat = $cat['name'];
            return $this->renderViewClient('book-list', [
                'books' => $books,
                'categoryId' => $name_cat
            ]);
        } catch (\Exception $e) {
            error_log("Error in listByCategory for categoryId $categoryId: " . $e->getMessage());
            return $this->renderViewClient('errors.index', ['message' => 'Có lỗi xảy ra khi tải danh sách sách.']);
        }
    }
}