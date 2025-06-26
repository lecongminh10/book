<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\BookBorrowing;
use Lecon\Mvcoop\Models\User;
use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\Post;
use Lecon\Mvcoop\Models\Shelf;
use Lecon\Mvcoop\Models\ShelfPosition;

class DashboardController extends Controller
{
    private Book $book;
    private Category $category;
    private User $user;
    private BookBorrowing $bookBorrowing;
    private Post $post;
    private Shelf $shelf;

    public function __construct()
    {
        $this->book = new Book();
        $this->category = new Category();
        $this->user = new User();
        $this->bookBorrowing = new BookBorrowing();
        $this->post = new Post();
        $this->shelf = new Shelf();
    }

    public function index()
    {
        // Thống kê tổng quan
        $totalBooks = $this->book->getTotalBooksCount();
        $totalUsers = $this->user->getTotalUsersCount();
        $totalBorrowings = $this->bookBorrowing->getTotalBorrowingsCount();
        $totalCategories = $this->category->getTotalCategoriesCount();
        $totalPosts = $this->post->getTotalPostsCount();
        $totalShelves = $this->shelf->getTotalShelvesCount();

        // Thống kê sách
        $booksByCategory = $this->book->getBooksByCategoryAll();
        $booksByRating = $this->book->getBooksByRating();
        $booksByShelf = $this->book->getBooksByShelf();
        $latestBooks = $this->book->getLatestBooks(5);
        $featuredBooks = $this->book->getFeaturedBooks();
        $booksByYear = $this->book->getBooksByPublishYear();

        // Thống kê người dùng
        $latestUsers = $this->user->getLatestUsers(5);
        $usersByRole = $this->user->getUsersByRole();

        // Thống kê mượn sách
        $borrowingsByStatus = $this->bookBorrowing->getBorrowingsByStatus();
        $recentBorrowings = $this->bookBorrowing->getRecentBorrowings(10);
        $mostBorrowedBooks = $this->bookBorrowing->getMostBorrowedBooks(10);
        $mostActiveBorrowers = $this->bookBorrowing->getMostActiveBorrowers(10);

        // Thống kê bài viết
        $postsByStatus = $this->post->getPostsByStatus();
        $latestPosts = $this->post->getLatestPosts(5);

        // Thống kê kệ sách
        $shelvesWithBooks = $this->shelf->getShelvesWithBooks();

        // Truyền dữ liệu tới view
        $this->renderViewAdmin('dashboard', [
            // Tổng quan
            'totalBooks' => $totalBooks,
            'totalUsers' => $totalUsers,
            'totalBorrowings' => $totalBorrowings,
            'totalCategories' => $totalCategories,
            'totalPosts' => $totalPosts,
            'totalShelves' => $totalShelves,
            
            // Thống kê sách
            'booksByCategory' => $booksByCategory,
            'booksByRating' => $booksByRating,
            'booksByShelf' => $booksByShelf,
            'latestBooks' => $latestBooks,
            'featuredBooks' => $featuredBooks,
            'booksByYear' => $booksByYear,
            
            // Thống kê người dùng
            'latestUsers' => $latestUsers,
            'usersByRole' => $usersByRole,
            
            // Thống kê mượn sách
            'borrowingsByStatus' => $borrowingsByStatus,
            'recentBorrowings' => $recentBorrowings,
            'mostBorrowedBooks' => $mostBorrowedBooks,
            'mostActiveBorrowers' => $mostActiveBorrowers,
            
            // Thống kê bài viết
            'postsByStatus' => $postsByStatus,
            'latestPosts' => $latestPosts,
            
            // Thống kê kệ sách
            'shelvesWithBooks' => $shelvesWithBooks,
        ]);
    }
}