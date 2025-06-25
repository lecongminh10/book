<?php
namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Category_Post;
use Lecon\Mvcoop\Models\Post;
use Lecon\Mvcoop\Models\User;

class PostController extends Controller
{
    private $post;
    private $categoryPostModel;
    private $user;


    public function __construct()
    {
        $this->post = new Post();
        $this->categoryPostModel = new Category_Post();
        $this->user= new User();
    }
    public function index()
    {
        $limit = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        $posts = $this->post->getAllWithCategory($limit, $offset);
        // Lấy tổng số bài viết để tính tổng số trang
        $total = $this->post->countAll();
        $totalPages = ceil($total / $limit);
        return $this->renderViewAdmin('posts.index', [
            'posts' => $posts,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $content = $_POST['content_post'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $user_id = $_POST['user_id'] ?? null;
            $status = $_POST['status'] ?? 'draft';
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../uploads/posts/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = '/uploads/posts/' . $fileName;
                }
            }
            $postModel = new Post();
            try {
                $postModel->insert($title, $slug, $content, $image, $category_id, $user_id, $status);
                $_SESSION['success'] = 'Thêm bài viết thành công!';
                header('Location: /admin/posts');
                exit;
            } catch (\Throwable $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                header('Location: /admin/posts/create');
                exit;
            }
        }
        $categories = $this->categoryPostModel->getAll();
        $users = $this->user->getAuthor();
        return $this->renderViewAdmin('posts.create', [
            'categories' => $categories,
            'users' =>$users
        ]);
    }

    public function update($id)
    {
        $postModel = new Post();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $content = $_POST['content_post'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $user_id = $_POST['user_id'] ?? null;
            $status = $_POST['status'] ?? 'draft';
            $image = $_POST['old_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../uploads/posts/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = '/uploads/posts/' . $fileName;
                }
            }
            try {
                $postModel->update($id, $title, $slug, $content, $image, $category_id, $user_id, $status);
                $_SESSION['success'] = 'Cập nhật bài viết thành công!';
                header('Location: /admin/posts');
                exit;
            } catch (\Throwable $e) {
                $_SESSION['error'] = 'Có lỗi khi cập nhật: ' . $e->getMessage();
                header('Location: /admin/posts/' . $id . '/update');
                exit;
            }
        }
        $post = $postModel->getById($id);
        $categories = $this->categoryPostModel->getAll();
        $users = $this->user->getAuthor();
        return $this->renderViewAdmin('posts.update', [
            'post' => $post,
            'categories' => $categories,
            'users' => $users
        ]);
    }

    public function delete($id)
    {
        $postModel = new Post();
        try {
            $postModel->delete($id);
            $_SESSION['success'] = 'Xóa bài viết thành công!';
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Có lỗi khi xóa: ' . $e->getMessage();
        }
        header('Location: /admin/posts');
        exit;
    }
}