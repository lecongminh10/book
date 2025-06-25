<?php

namespace Lecon\Mvcoop\Models;

use Lecon\Mvcoop\Commons\Model;

class Profile extends Model
{
    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    // Get user by ID
    public function getUserById($id)
    {
        try {
            $sql = "SELECT id, student_id, username, email, full_name, role, created_at, updated_at FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Update user information
    public function updateUser($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} 
                    SET student_id = :student_id, 
                        username = :username, 
                        email = :email, 
                        full_name = :full_name, 
                        updated_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':student_id', $data['student_id']);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            // Log error but don't die, return false instead
            error_log('Update user error: ' . $e->getMessage());
            return false;
        }
    }

    // Update user password
    public function updatePassword($id, $hashedPassword)
    {
        try {
            $sql = "UPDATE {$this->table} SET password = :password, updated_at = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            // Log error but don't die, return false instead
            error_log('Update password error: ' . $e->getMessage());
            return false;
        }
    }

    // Get user password for verification
    public function getUserPassword($id)
    {
        try {
            $sql = "SELECT password FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result ? $result['password'] : null;
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Check if student_id exists (excluding current user)
    public function isStudentIdExists($studentId, $excludeId = null)
    {
        try {
            if ($excludeId) {
                $sql = "SELECT id FROM {$this->table} WHERE student_id = :student_id AND id != :exclude_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':student_id', $studentId);
                $stmt->bindParam(':exclude_id', $excludeId);
            } else {
                $sql = "SELECT id FROM {$this->table} WHERE student_id = :student_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':student_id', $studentId);
            }
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Check if username exists (excluding current user)
    public function isUsernameExists($username, $excludeId = null)
    {
        try {
            if ($excludeId) {
                $sql = "SELECT id FROM {$this->table} WHERE username = :username AND id != :exclude_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':exclude_id', $excludeId);
            } else {
                $sql = "SELECT id FROM {$this->table} WHERE username = :username";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':username', $username);
            }
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Check if email exists (excluding current user)
    public function isEmailExists($email, $excludeId = null)
    {
        try {
            if ($excludeId) {
                $sql = "SELECT id FROM {$this->table} WHERE email = :email AND id != :exclude_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':exclude_id', $excludeId);
            } else {
                $sql = "SELECT id FROM {$this->table} WHERE email = :email";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':email', $email);
            }
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Get user borrowing history with book details
    public function getBorrowHistory($userId)
    {
        try {
            $sql = "SELECT bb.*, b.title, b.author, b.isbn 
                    FROM book_borrowings bb 
                    JOIN books b ON bb.book_id = b.id 
                    WHERE bb.user_id = :user_id 
                    ORDER BY bb.created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Get borrowing statistics for user
    public function getBorrowingStats($userId)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_borrowings,
                        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                        SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned_count,
                        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
                    FROM book_borrowings 
                    WHERE user_id = :user_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Get active borrowings (approved but not returned)
    public function getActiveBorrowings($userId)
    {
        try {
            $sql = "SELECT bb.*, b.title, b.author, b.isbn 
                    FROM book_borrowings bb 
                    JOIN books b ON bb.book_id = b.id 
                    WHERE bb.user_id = :user_id AND bb.status = 'approved'
                    ORDER BY bb.return_date ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    // Get overdue borrowings
    public function getOverdueBorrowings($userId)
    {
        try {
            $sql = "SELECT bb.*, b.title, b.author, b.isbn 
                    FROM book_borrowings bb 
                    JOIN books b ON bb.book_id = b.id 
                    WHERE bb.user_id = :user_id 
                    AND bb.status = 'approved' 
                    AND bb.return_date < CURDATE()
                    ORDER BY bb.return_date ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
}