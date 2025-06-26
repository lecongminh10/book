<?php

namespace Lecon\Mvcoop\Models;

use Lecon\Mvcoop\Commons\Model;

class User extends Model
{
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM users";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getAuthor() {
        try {
            $sql = "SELECT * FROM users WHERE student_id IS NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
    
    public function getByID($id)
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function insert($name, $email, $password)
    {
        try {
            $sql = "
                INSERT INTO users(name, email, password) 
                VALUES (:name, :email, :password)
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function update($id, $student_id, $username, $full_name, $email, $role)
    {
        try {
            $sql = "
                UPDATE users 
                SET student_id = :student_id,
                    username = :username,
                    full_name = :full_name,
                    email = :email,
                    role = :role
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);

            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function deleteByID($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);

            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getByEmailAndPassword($email, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
            $stmt = $this->conn->prepare($sql);
            $md5Password = md5($password); // Đảm bảo mật khẩu được mã hóa MD5
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $md5Password);
            $stmt->execute();
            $result = $stmt->fetch();
            // Debug
            if (!$result) {
                error_log("No user found for email: $email, password: $md5Password");
            }
            return $result;
        } catch (\Exception $e) {
            error_log("Error in getByEmailAndPassword: " . $e->getMessage());
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function insertFull($student_id, $username, $password, $email, $full_name, $role)
    {
        $sql = "INSERT INTO users (student_id, username, password, email, full_name, role, created_at, updated_at)
                VALUES (:student_id, :username, :password, :email, :full_name, :role, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }
    public function getByUsername($username)
    {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getByStudentId($student_id)
    {
        try {
            $sql = "SELECT * FROM users WHERE student_id = :student_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getByEmail($email)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
    public function getTotalUsersCount()
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Thống kê người dùng mới nhất
    public function getLatestUsers($limit = 5)
    {
        $sql = "
            SELECT id, username, email, full_name, role, created_at
            FROM users
            WHERE role != 'admin'
            ORDER BY created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    // Thống kê người dùng theo vai trò
    public function getUsersByRole()
    {
        $sql = "
            SELECT role, COUNT(*) AS user_count
            FROM users
            GROUP BY role
            ORDER BY user_count DESC
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}
