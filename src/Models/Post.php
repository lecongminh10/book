<?php
namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;

class Post extends Model
{
    public function getAllWithCategory($limit = null, $offset = null)
    {
        $sql = "SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories_post c ON p.category_id = c.id ORDER BY p.created_at DESC";
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->conn->prepare($sql);
        if ($limit !== null && $offset !== null) {
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert($title, $slug, $content, $image, $category_id, $user_id, $status = 'draft') {
        try {
            $sql = "INSERT INTO posts (title, slug, content, image, category_id, user_id, status) VALUES (:title, :slug, :content, :image, :category_id, :user_id, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM posts";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    public function update($id, $title, $slug, $content, $image, $category_id, $user_id, $status) {
        $sql = "UPDATE posts SET title = :title, slug = :slug, content = :content, image = :image, category_id = :category_id, user_id = :user_id, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getById($id){
        $sql = "SELECT * FROM posts WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getNewOnePost() {
        $sql = "SELECT p.id, p.title, p.slug, p.content, p.image, p.created_at, p.updated_at, p.status, c.name AS category,
                       SUBSTRING(p.content, 1, 100) AS short,
                       CONCAT('/post/', p.slug) AS link
                FROM posts p
                LEFT JOIN categories_post c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.id DESC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function getNextFourPosts() {
        $sql = "SELECT p.id, p.title, p.slug, p.content, p.image, p.created_at, p.updated_at, p.status, c.name AS category,
                       SUBSTRING(p.content, 1, 100) AS short,
                       CONCAT('/post/', p.slug) AS link
                FROM posts p
                LEFT JOIN categories_post c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.id DESC
                LIMIT 4 OFFSET 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    // Thống kê tổng số bài viết
public function getTotalPostsCount()
{
    $sql = "SELECT COUNT(*) as total FROM posts";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}
public function getPostsByStatus()
{
    $sql = "
        SELECT status, COUNT(*) AS post_count
        FROM posts
        GROUP BY status
        ORDER BY post_count DESC
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

// Thống kê bài viết mới nhất
public function getLatestPosts($limit = 5)
{
    $sql = "
        SELECT p.*, cp.name AS category_name, u.full_name AS author_name
        FROM posts p
        LEFT JOIN categories_post cp ON p.category_id = cp.id
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT :limit
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
}

}