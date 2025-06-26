<?php

namespace Lecon\Mvcoop\Models;
use Lecon\Mvcoop\Commons\Model;
class BookRating extends Model {

    public function hasBookRating($userId, $bookId) {
        $sql = "SELECT id, rating, comment, created_at, updated_at FROM book_ratings WHERE user_id = ? AND book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $bookId]);
        return $stmt->fetch();
    }

    public function createRating($userId, $bookId, $rating, $comment) {
        $sql = "INSERT INTO book_ratings (user_id, book_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $bookId, $rating, $comment]);
    }

    public function updateRating($ratingId, $rating, $comment) {
        $sql = "UPDATE book_ratings SET rating = ?, comment = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$rating, $comment, $ratingId]);
    }

    public function getRatingByUserAndBook($userId, $bookId) {
        $sql = "SELECT id, rating, comment, created_at, updated_at FROM book_ratings WHERE user_id = ? AND book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $bookId]);
        return $stmt->fetch();
    }
}