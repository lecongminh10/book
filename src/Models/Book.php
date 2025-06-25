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
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                ORDER BY b.created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
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
} 