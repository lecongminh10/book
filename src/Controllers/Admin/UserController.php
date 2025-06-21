<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\User;

class UserController extends Controller
{
    private User $user;

    private string $folder = 'users.';

    public function __construct()
    {
        $this->user = new User;
    }

    // danh sach 

    public function index()
    {
        $data['users'] = $this->user->getAll();

        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }
    // Thêm mới
    public function create()
    {
        if (!empty($_POST)) {
            $student_id = $_POST['student_id'] ?? null;
            $username = $_POST['username'] ?? null;
            $full_name = $_POST['full_name'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = isset($_POST['password']) ? md5($_POST['password']) : null;
            $role = (!empty($_POST['role']) && in_array($_POST['role'], ['admin', 'user'])) ? $_POST['role'] : 'user';

            $this->user->insertFull($student_id, $username, $password, $email, $full_name, $role);

            header('Location: /admin/users');
            exit();
        }
        return $this->renderViewAdmin($this->folder . __FUNCTION__);
    }

    // Xem chi tiết theo ID
    public function show($id)
    {
        $data['user'] = $this->user->getByID($id);

        if (empty($data['user'])) {
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
        $data['user'] = $this->user->getByID($id);

        if (empty($data['user'])) {
            die(404);
        }
        if (!empty($_POST)) {
            $student_id = $_POST['student_id'] ?? null;
            $username = $_POST['username'] ?? null;
            $full_name = $_POST['full_name'] ?? null;
            $email = $_POST['email'] ?? null;
            $role = (!empty($_POST['role']) && in_array($_POST['role'], ['admin', 'user'])) ? $_POST['role'] : 'user';

            $this->user->update($id, $student_id, $username, $full_name, $email, $role);
            $_SESSION['success'] = 'Thao tác thành công';
            header('Location:/admin/users/');
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
        $this->user->deleteByID($id);
        header('Location:/admin/users/');
        exit();
        // return $this->renderViewAdmin($this->folder . __FUNCTION__);
    }
}
