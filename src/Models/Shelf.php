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
    
}