<?php

namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;
class Shelf extends Model {
    public function getAllShelf() {
        $sql = "SELECT * FROM shelves ORDER BY id ASC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function deleteShelfAndPositions($shelf_id) {
        $sql1 = "DELETE FROM shelf_positions WHERE shelf_id = ?";
        $sql2 = "DELETE FROM shelves WHERE id = ?";
        $this->conn->beginTransaction();
        try {
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([$shelf_id]);
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([$shelf_id]);
            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getShelvesPaginated($limit = 10, $offset = 0) {
        $sql = "SELECT * FROM shelves ORDER BY id ASC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countShelves() {
        $sql = "SELECT COUNT(*) as total FROM shelves";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? (int)$row['total'] : 0;
    }

    public function findById($id) {
        $sql = "SELECT * FROM shelves WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateShelf($id, $name, $location_note) {
        $sql = "UPDATE shelves SET name = ?, location_note = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $location_note, $id]);
    }
    public function getTotalShelvesCount()
{
    $sql = "SELECT COUNT(*) as total FROM shelves";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// Thống kê vị trí kệ có sách
public function getShelvesWithBooks()
{
    $sql = "
        SELECT s.name AS shelf_name, COUNT(b.id) AS book_count
        FROM shelves s
        LEFT JOIN shelf_positions sp ON s.id = sp.shelf_id
        LEFT JOIN books b ON sp.id = b.shelf_position_id
        GROUP BY s.id, s.name
        ORDER BY book_count DESC
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}
}