<?php
namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;

class Category_Post extends Model
{
    protected string $tableName = 'categories_post';

    // Create: Insert a new category
    public function create($name, $slug, $description = null)
    {
        $sql = "INSERT INTO {$this->tableName} (name, slug, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name, $slug, $description]);
        return $this->conn->lastInsertId(); // Return the ID of the newly created category
    }

    // Read: Get all categories
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->tableName} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Read: Get a single category by ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Update: Update a category by ID
    public function update($id, $name, $slug, $description = null)
    {
        $sql = "UPDATE {$this->tableName} SET name = ?, slug = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $slug, $description, $id]); // Return true if successful
    }

    // Delete: Delete a category by ID
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->tableName} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]); // Return true if successful
    }

    // Optional: Get category by slug
    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE slug = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}