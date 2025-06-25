<?php

namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;

class ShelfPosition extends Model {
    public function insertMany($shelf_id, $positions)
    {
        if (empty($positions)) {
            return false;
        }
        $conn = $this->getConnection();
        $sql = "INSERT INTO shelf_positions (shelf_id, title, color, position_x, position_y) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($positions as $position) {
            $stmt->execute([
                $shelf_id,
                $position['title'],
                $position['color'],
                $position['position_x'],
                $position['position_y']
            ]);
        }
        return true;
    }
    
    /**
     * Lấy tất cả vị trí của một kệ
     */
    public function getByShelfId($shelf_id)
    {
        $sql = "SELECT * FROM shelf_positions WHERE shelf_id = ? ORDER BY position_y, position_x";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([$shelf_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Xóa tất cả vị trí của một kệ
     */
    public function deleteByShelfId($shelf_id)
    {
        $sql = "DELETE FROM shelf_positions WHERE shelf_id = ?";
        $stmt = $this->getConnection()->prepare($sql);
        return $stmt->execute([$shelf_id]);
    }
    
    /**
     * Cập nhật vị trí cho một kệ (xóa cũ, thêm mới)
     */
    public function updateShelfPositions($shelf_id, $positions)
    {
        $conn = $this->getConnection();
        
        try {
            $conn->beginTransaction();
            
            // Xóa các vị trí cũ
            $this->deleteByShelfId($shelf_id);
            
            // Thêm vị trí mới
            if (!empty($positions)) {
                $this->insertMany($shelf_id, $positions);
            }
            
            $conn->commit();
            return true;
            
        } catch (\Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    
} 