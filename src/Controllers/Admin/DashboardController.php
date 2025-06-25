<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\BookBorrowing;
use Lecon\Mvcoop\Models\User;
use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\Category;

class DashboardController extends Controller
{
    private Book $book;
    private Category $category;

    public function __construct()
    {
        $this->book = new Book();
        $this->category = new Category();
    }

    public function index()
    {
        // Thống kê sách theo danh mục
        $booksByCategory = $this->book->getBooksByCategoryAll();

        // Thống kê sách theo đánh giá trung bình
        $booksByRating = $this->book->getBooksByRating();

        // Thống kê sách theo vị trí kệ
        $booksByShelf = $this->book->getBooksByShelf();

        // Truyền dữ liệu tới view
        $this->renderViewAdmin('dashboard', [
            'booksByCategory' => $booksByCategory,
            'booksByRating' => $booksByRating,
            'booksByShelf' => $booksByShelf
        ]);
    }
}