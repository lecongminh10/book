<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Category;

class CategoryController extends Controller
{
    private Category $category;

    private string $folder = 'categorys.';

    public function __construct()
    {
        $this->category = new Category;
    }

    // danh sach 

    public function index()
    {
        $data['categorys'] = $this->category->getAll();

        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }
    // Thêm mới
    public function create()
    {
        if (!empty($_POST)) {


            $this->category->insert($_POST['name']);

            header('Location: /admin/categorys');
            exit();
        }

        return $this->renderViewAdmin($this->folder . __FUNCTION__);
    }

    // Xem chi tiết theo ID
    public function show($id)
    {
        $data['category'] = $this->category->getByID($id);

        if (empty($data['category'])) {
            die(404);
        }

        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    // Cập nhật theo ID
    public function update($id)
    {
        $data['category'] = $this->category->getByID($id);

        if (empty($data['category'])) {
            die(404);
        }
        if (!empty($_POST)) {

            $name = $_POST['name'];
            $this->category->update($id, $name);
            $_SESSION['success'] = 'Thao tác thành công';
            header("Location:/admin/categorys/$id/update");
            exit();
        }
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    // Delete theo ID
    public function delete($id)
    {
        $this->category->deleteByID($id);
        header('Location:/admin/categorys/');
        exit();
       
    }
}
