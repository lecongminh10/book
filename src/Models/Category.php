<?php
namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;

class Category extends Model
{
    public function getForMenu() {
        try {    
            $sql = "SELECT id , name  FROM categories";
    
            $stmt = $this->conn->prepare($sql);
        
            $stmt->execute();
        
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
    public function getAll() {
        try {    
            $sql = "SELECT * FROM categories";
    
            $stmt = $this->conn->prepare($sql);
        
            $stmt->execute();
        
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getByID($id) {
        try {    
            $sql = "SELECT * FROM categories WHERE id = :id";
    
            $stmt = $this->conn->prepare($sql);
        
            $stmt->bindParam(':id', $id);

            $stmt->execute();
        
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function insert($name) {
        try {    
            $sql = "
                INSERT INTO categories(name) 
                VALUES (:name)
            ";
    
            $stmt = $this->conn->prepare($sql);
        
            $stmt->bindParam(':name', $name);


            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function update($id, $name) {
        try {    
            $sql = "
                UPDATE categories 
                SET name = :name 
                WHERE id = :id
            ";
    
            $stmt = $this->conn->prepare($sql);
        
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);

            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function deleteByID($id) {
        try {    
            $sql = "DELETE FROM categories WHERE id = :id";
    
            $stmt = $this->conn->prepare($sql);
        
            $stmt->bindParam(':id', $id);

            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
public function getTotalCategoriesCount()
{
    $sql = "SELECT COUNT(*) as total FROM categories";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}
}