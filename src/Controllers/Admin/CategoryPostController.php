<?php
namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Category_Post;

class CategoryPostController extends Controller
{
    public function index()
    {
        $categoryPostModel = new Category_Post();
        $categories = $categoryPostModel->getAll();
        return $this->renderViewAdmin('category-post.index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $description = $_POST['description'] ?? null;
            $categoryPostModel = new Category_Post();
            try {
                $categoryPostModel->create($name, $slug, $description);
                $_SESSION['success'] = 'Thêm danh mục thành công!';
                header('Location: /admin/category-post');
                exit;
            } catch (\Throwable $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                header('Location: /admin/category-post/create');
                exit;
            }
        }
        return $this->renderViewAdmin('category-post.create');
    }

    public function delete($id)
    {
        if ($id) {
            $categoryPostModel = new Category_Post();
            try {
                $categoryPostModel->delete($id);
                $_SESSION['success'] = 'Xóa danh mục thành công!';
            } catch (\Throwable $e) {
                $_SESSION['error'] = 'Có lỗi khi xóa: ' . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'Không tìm thấy ID danh mục.';
        }
        header('Location: /admin/category-post');
        exit;
    }

    public function update($id)
    {
        $categoryPostModel = new Category_Post();
        $category = $categoryPostModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = 'Không tìm thấy danh mục.';
            header('Location: /admin/category-post');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $description = $_POST['description'] ?? null;
            $categoryPostModel = new Category_Post();
            try {
                $categoryPostModel->update($id, $name, $slug, $description);
                $_SESSION['success'] = 'Cập nhật danh mục thành công!';
                header('Location: /admin/category-post');
                exit;
            } catch (\Throwable $e) {
                $_SESSION['error'] = 'Có lỗi khi cập nhật: ' . $e->getMessage();
                header('Location: /admin/category-post/update?id=' . $id);
                exit;
            }
        }
        return $this->renderViewAdmin('category-post.update', ['category' => $category]);
    }

}
