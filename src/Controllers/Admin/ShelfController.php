<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Models\BookBorrowing;
use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\Shelf;
use Lecon\Mvcoop\Models\User;

class ShelfController extends Controller {
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
}