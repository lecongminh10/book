<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Models\BookBorrowing;
use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\Shelf;
use Lecon\Mvcoop\Models\User;

class BookController extends Controller {
    private $bookModel;
    private $categoryModel;
    private $userModel;
    private $shelfModel;
    private string $folder = 'books.';
    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
        $this->userModel = new User();
        $this->shelfModel = new Shelf();
    }

    public function index() {
        $perPage = 5;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $perPage;
        $books = $this->bookModel->getBooksPaginated($perPage, $offset);
        $totalBooks = $this->bookModel->countBooks();
        $totalPages = ceil($totalBooks / $perPage);
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            [
                'books' => $books,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]
        );
    }
    public function create() {
        // Xử lý khi submit form
        if (!empty($_POST)) {
            $data = [
                'title' => $_POST['title'] ?? '',
                'author' => $_POST['author'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'publish_year' => $_POST['publish_year'] ?? '',
                'isbn' => $_POST['isbn'] ?? '',
                'location_description' => $_POST['location_description'] ?? '',
                'summary' => $_POST['summary'] ?? '',
                'content' => $_POST['content'] ?? '',
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            ];

            // Xử lý upload file
            $uploadDir = __DIR__ . '/../../../uploads/books/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            // Bìa trước
            $data['cover_front'] = null;
            if (isset($_FILES['cover_front']) && $_FILES['cover_front']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['cover_front']['name'], PATHINFO_EXTENSION);
                $fileName = 'front_' . time() . '_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['cover_front']['tmp_name'], $targetPath)) {
                    $data['cover_front'] = 'uploads/books/' . $fileName;
                }
            }
            // Bìa sau
            $data['cover_back'] = null;
            if (isset($_FILES['cover_back']) && $_FILES['cover_back']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['cover_back']['name'], PATHINFO_EXTENSION);
                $fileName = 'back_' . time() . '_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['cover_back']['tmp_name'], $targetPath)) {
                    $data['cover_back'] = 'uploads/books/' . $fileName;
                }
            }
            // Lưu vào DB
            $this->bookModel->createBook($data);
            header('Location: /admin/books');
            exit();
        }
        // Nếu không có POST, render form như cũ
        $categories = $this->categoryModel->getAll();
        $authors = $this->userModel->getAuthor();
        $shelves = $this->shelfModel->getAllShelf();
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            ['categories' => $categories, 'authors' => $authors ,'shelves'=>$shelves]
        );
    }
} 