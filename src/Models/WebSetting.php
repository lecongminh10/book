<?php

namespace Lecon\Mvcoop\Models;

use Lecon\Mvcoop\Commons\Model;

class WebSetting extends Model
{
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM web_setting";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getByName($name)
    {
        try {
            $sql = "SELECT * FROM web_setting WHERE name = :name";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function update($id, $title, $value)
    {
        try {
            $sql = "UPDATE web_setting SET title = :title, value = :value, updated_at = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM web_setting WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function insertFull($title, $name, $value)
    {
        try {
            $sql = "INSERT INTO web_setting (title, name, value, created_at, updated_at) VALUES (:title, :name, :value, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }

    public function deleteById($id)
    {
        try {
            $sql = "DELETE FROM web_setting WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
}