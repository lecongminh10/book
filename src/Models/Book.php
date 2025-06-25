<?php

namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;
class Book extends Model {
    public function getAllBooks() {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeaturedBooks() {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.is_featured = 1 
                ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBookById($id) {
        $sql = "SELECT b.*, c.name as category_name, sp.title as shelf_position_title
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                LEFT JOIN shelf_positions sp ON b.shelf_position_id = sp.id
                WHERE b.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function searchBooks($keyword) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.title LIKE ? OR b.author LIKE ? OR b.summary LIKE ?";
        
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    public function getBooksByCategory($categoryId) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.category_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function createBook($data) {
        $sql = "INSERT INTO books (title, author, category_id, publish_year, isbn, 
                location_description, cover_front, cover_back, summary, content, is_featured, shelf_position_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['category_id'],
            $data['publish_year'],
            $data['isbn'],
            $data['location_description'],
            $data['cover_front'],
            $data['cover_back'],
            $data['summary'],
            $data['content'],
            $data['is_featured'] ?? 0,
            $data['shelf_id'] ?? null
        ]);
    }

    public function updateBook($id, $data) {
        $sql = "UPDATE books SET 
                title = ?, 
                author = ?, 
                category_id = ?, 
                publish_year = ?, 
                isbn = ?, 
                location_description = ?, 
                cover_front = ?, 
                cover_back = ?, 
                summary = ?, 
                content = ?, 
                is_featured = ?,
                shelf_position_id = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['category_id'],
            $data['publish_year'],
            $data['isbn'],
            $data['location_description'],
            $data['cover_front'],
            $data['cover_back'],
            $data['summary'],
            $data['content'],
            $data['is_featured'] ?? 0,
            $data['shelf_position_id'] ?? null,
            $id
        ]);
    }

    public function deleteBook($id) {
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function isBookAvailable($bookId) {
        $sql = "SELECT COUNT(*) as count FROM book_borrowings 
                WHERE book_id = ? AND status = 'approved'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookId]);
        $result = $stmt->fetch();
        return $result['count'] == 0;
    }

    public function getBooksPaginated($limit, $offset) {
        $sql = "SELECT 
                    b.*, 
                    c.name AS category_name,
                    AVG(b_r.rating) AS average_rating
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                LEFT JOIN book_ratings b_r ON b_r.book_id = b.id
                GROUP BY b.id
                ORDER BY b.created_at DESC 
                LIMIT :limit OFFSET :offset";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function new_getBoosk() {
        $sql = "SELECT 
                    b.*, 
                    c.name AS category_name,
                    AVG(b_r.rating) AS average_rating
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                LEFT JOIN book_ratings b_r ON b_r.book_id = b.id
                GROUP BY b.id
                ORDER BY b.created_at DESC 
                LIMIT 6";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }    
    public function hot_getBoosk() {
        $sql = "SELECT 
                    b.*, 
                    c.name AS category_name,
                    AVG(b_r.rating) AS average_rating
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                LEFT JOIN book_ratings b_r ON b_r.book_id = b.id
                WHERE b.is_featured=1
                GROUP BY b.id
                ORDER BY b.created_at DESC 
                LIMIT 6";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }   
    public function countBooks() {
        $sql = "SELECT COUNT(*) as total FROM books";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? (int)$result['total'] : 0;
    }
public function getBooksByCategoryAll()
    {
        $sql = "
            SELECT c.name AS category_name, COUNT(b.id) AS book_count
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.id
            GROUP BY c.id, c.name
            ORDER BY book_count DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function getBooksByRating()
    {
        $sql = "
            SELECT b.title, AVG(br.rating) AS avg_rating, COUNT(br.id) AS rating_count
            FROM books b
            LEFT JOIN book_ratings br ON b.id = br.book_id
            GROUP BY b.id, b.title
            HAVING avg_rating IS NOT NULL
            ORDER BY avg_rating DESC
            LIMIT 10
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function getBooksByShelf()
    {
        $sql = "
            SELECT b.shelf_position_id, COUNT(b.id) AS book_count
            FROM books b
            WHERE b.shelf_position_id IS NOT NULL
            GROUP BY b.shelf_position_id
            ORDER BY b.shelf_position_id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    public function list_book_cat($id) {
            if (!is_numeric($id) || $id <= 0) {
                error_log("Invalid category ID: $id");
                return [];
            }

            // Query books with average rating, grouped by book
            $sql = "SELECT books.id, books.title, books.author, books.category_id, books.publish_year, 
                           books.isbn, books.location_description, books.cover_front, books.cover_back, 
                           books.summary, books.content, books.is_featured, books.shelf_position_id,
                           COALESCE(AVG(b_r.rating), 0) AS average_rating
                    FROM books 
                    LEFT JOIN book_ratings b_r ON b_r.book_id = books.id
                    WHERE books.category_id = ?
                    GROUP BY books.id, books.title, books.author, books.category_id, books.publish_year, 
                             books.isbn, books.location_description, books.cover_front, books.cover_back, 
                             books.summary, books.content, books.is_featured, books.shelf_position_id
                    ORDER BY books.title ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $books = $stmt->fetchAll();

            // Log result count
            error_log("Found " . count($books) . " books for category_id: $id");

            return $books ?: [];
    }
} 