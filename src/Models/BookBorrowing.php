<?php

namespace Lecon\Mvcoop\Models;

use Lecon\Mvcoop\Database\Database;
use Lecon\Mvcoop\Commons\Model;

class BookBorrowing extends Model {
    public function createBorrowing($userId, $bookId, $borrowDate, $returnDate) {
        $sql = "INSERT INTO book_borrowings (user_id, book_id, borrow_date, return_date, status) 
                VALUES (?, ?, ?, ?, 'pending')";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $bookId, $borrowDate, $returnDate]);
    }

    public function getUserBorrowings($userId) {
        $sql = "SELECT bb.*, b.title, b.author, b.cover_front 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                WHERE bb.user_id = ? 
                ORDER BY bb.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAllBorrowings() {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                JOIN users u ON bb.user_id = u.id 
                ORDER BY bb.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingBorrowings() {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                JOIN users u ON bb.user_id = u.id 
                WHERE bb.status = 'pending' 
                ORDER BY bb.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateBorrowingStatus($id, $status) {
        $sql = "UPDATE book_borrowings SET status = ? WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function getBorrowingById($id) {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                JOIN users u ON bb.user_id = u.id 
                WHERE bb.id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getActiveBorrowings() {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                JOIN users u ON bb.user_id = u.id 
                WHERE bb.status = 'approved' 
                ORDER BY bb.return_date ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBorrowingStatistics() {
        $sql = "SELECT 
                COUNT(*) as total_borrowings,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned_count,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
                FROM book_borrowings";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getReturnedBorrowings() {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                JOIN users u ON bb.user_id = u.id 
                WHERE bb.status = 'returned' 
                ORDER BY bb.return_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRejectedBorrowings() {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id 
                FROM book_borrowings bb 
                JOIN books b ON bb.book_id = b.id 
                JOIN users u ON bb.user_id = u.id 
                WHERE bb.status = 'rejected' 
                ORDER BY bb.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBorrowingsPaginated($status = null, $limit = 10, $offset = 0) {
        $where = '';
        $params = [];
        if ($status) {
            $where = "WHERE bb.status = ?";
            $params[] = $status;
        }
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.student_id
                FROM book_borrowings bb
                JOIN books b ON bb.book_id = b.id
                JOIN users u ON bb.user_id = u.id
                $where
                ORDER BY bb.created_at DESC
                LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countBorrowings($status = null) {
        $where = '';
        $params = [];
        if ($status) {
            $where = "WHERE status = ?";
            $params[] = $status;
        }
        $sql = "SELECT COUNT(*) as total FROM book_borrowings $where";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? (int)$result['total'] : 0;
    }
    public function hasPendingBorrowing($userId, $bookId) {
        $sql = "SELECT id FROM book_borrowings 
                WHERE user_id = ? AND book_id = ? AND status = 'pending'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $bookId]);
        return $stmt->fetch() !== false;
    }
    public function getOverdueBorrowings() {
        $sql = "SELECT bb.*, b.title, b.author, u.full_name, u.email, u.phone
                FROM book_borrowings bb
                LEFT JOIN books b ON bb.book_id = b.id
                LEFT JOIN users u ON bb.user_id = u.id
                WHERE bb.status = 'approved' AND bb.return_date < CURDATE()
                ORDER BY bb.return_date ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function returnBook($borrowingId) {
        $sql = "UPDATE book_borrowings SET status = 'returned', actual_return_date = NOW(), updated_at = NOW() WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$borrowingId]);
    }
    // Thống kê tổng số lượt mượn
public function getTotalBorrowingsCount()
{
    $sql = "SELECT COUNT(*) as total FROM book_borrowings";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// Thống kê theo trạng thái mượn
public function getBorrowingsByStatus()
{
    $sql = "
        SELECT status, COUNT(*) AS borrowing_count
        FROM book_borrowings
        GROUP BY status
        ORDER BY borrowing_count DESC
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

// Thống kê mượn sách gần đây
public function getRecentBorrowings($limit = 10)
{
    $sql = "
        SELECT bb.*, u.full_name, u.username, b.title
        FROM book_borrowings bb
        LEFT JOIN users u ON bb.user_id = u.id
        LEFT JOIN books b ON bb.book_id = b.id
        ORDER BY bb.created_at DESC
        LIMIT :limit
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

// Thống kê sách được mượn nhiều nhất
public function getMostBorrowedBooks($limit = 10)
{
    $sql = "
        SELECT b.title, b.author, COUNT(bb.id) AS borrow_count
        FROM books b
        LEFT JOIN book_borrowings bb ON b.id = bb.book_id
        GROUP BY b.id, b.title, b.author
        HAVING borrow_count > 0
        ORDER BY borrow_count DESC
        LIMIT :limit
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

// Thống kê người dùng mượn nhiều nhất
public function getMostActiveBorrowers($limit = 10)
{
    $sql = "
        SELECT u.full_name, u.username, COUNT(bb.id) AS borrow_count
        FROM users u
        LEFT JOIN book_borrowings bb ON u.id = bb.user_id
        GROUP BY u.id, u.full_name, u.username
        HAVING borrow_count > 0
        ORDER BY borrow_count DESC
        LIMIT :limit
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

    public function hasReturnBorrowing($userId, $borrowId) {
        $sql = "SELECT * FROM book_borrowings 
                WHERE user_id = ? AND id = ? AND status = 'returned'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $borrowId]);
        return $stmt->fetch();
    }
} 