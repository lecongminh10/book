<?php

namespace Lecon\Mvcoop\Controllers\Client;
use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Book;
use Lecon\Mvcoop\Models\User;

use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\WebSetting;
use Lecon\Mvcoop\Models\Post;



class HomeController extends Controller
{
    private User $user;
    private Category $category;
    private $book;
    private $post;
    public function __construct()
    {
        $this->category = new Category;
        $this->user = new User();
        $this->book = new Book();
        $this->post = new Post();
    }
    public function index()
    {
        $webSetting = new WebSetting();
        $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
        $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        // Lấy danh sách tất cả các category
        $categories = $this->category->getAll();

        // Lấy danh sách 3 bài viết hàng đầu
        // $postTop3 = $this->post->Top3FirsPost();

        // Lấy ra title đứng số 1 theo category
        $postFirstLatestTitle = [];
        // Lấy ra title đứng số 1 theo category 5
        $poststitileTradding = [];

        $postsByCategory = [];
        $postsByCategory2 = [];
        foreach ($categories as $category) {

        }

        $books = $this->book->new_getBoosk();
        $hot_books = $this->book->hot_getBoosk();
        $new_post = $this->post->getNewOnePost();
        $four_post = $this->post->getNextFourPosts();


        // Trả về view với dữ liệu được truyền đi
        return $this->renderViewClient(
            'home',
            [
                'categories' => $categories,
                'postsByCategory' => $postsByCategory,
                'postsByCategory2' => $postsByCategory2,
                'poststitileTradding' => $poststitileTradding,
                'postFirstLatestTitle' => $postFirstLatestTitle,
                'logo' => $logo,
                'slide_1' => $slide_1,
                'footer' => $footer,
                'hotline' => $hotline,
                'title_logo' => $title_logo,
                'books'=>$books,
                'hot_books'=>$hot_books,
                'new_post'=>$new_post,
                'four_post'=>$four_post
            ]
        );
    }
    public function categories()
    {
        $categories = new Category;
        $categories->getAll();
        return $this->renderViewClient('home', $categories);
    }
    public function login()
    {
        $webSetting = new WebSetting();
        $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
        $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        $categories = $this->category->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            error_log("Login attempt - Email: $email, Password: $password"); // Ghi log
            $user = $this->user->getByEmailAndPassword($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                error_log("Login successful for email: $email");
                header('Location: /');
                exit();
            } else {
                error_log("Login failed for email: $email");
                return $this->renderViewClient('login', ['error' => 'Invalid credentials']);
            }
        }
        return $this->renderViewClient('login',[
            'categories' => $categories,
            'logo' => $logo,
            'slide_1' => $slide_1,
            'footer' => $footer,
            'hotline' => $hotline,
            'title_logo' => $title_logo,
        ]);
    }

   public function register()
    {
        $webSetting = new WebSetting();
        $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
        $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        $categories = $this->category->getAll();
        // Kiểm tra nếu đã đăng nhập, chuyển hướng về trang chủ
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = md5($_POST['password']); // Mã hóa mật khẩu bằng MD5
            $student_id = $_POST['student_id'] ?? null;
            $username = $_POST['username'] ?? null;
            $full_name = $_POST['full_name'] ?? null;
            $role = 'user';

            // Kiểm tra trùng lặp student_id
            if ($student_id && $this->user->getByStudentId($student_id)) {
                return $this->renderViewClient('register', ['error' => 'Mã sinh viên đã tồn tại. Vui lòng chọn mã khác.']);
            }

            // Kiểm tra trùng lặp email
            if ($this->user->getByEmail($email)) {
                return $this->renderViewClient('register', ['error' => 'Email đã tồn tại. Vui lòng chọn email khác.']);
            }

            // Kiểm tra trùng lặp username
            if ($username && $this->user->getByUsername($username)) {
                return $this->renderViewClient('register', ['error' => 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.']);
            }

            $this->user->insertFull($student_id, $username, $password, $email, $full_name, $role);
            header('Location: /login');
            exit();
        }
        return $this->renderViewClient('register',[
            'categories' => $categories,
            'logo' => $logo,
            'slide_1' => $slide_1,
            'footer' => $footer,
            'hotline' => $hotline,
            'title_logo' => $title_logo,
        ]);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit();
    }

    public function about()
    {   $webSetting = new WebSetting();
        $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
        $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        $categories = $this->category->getAll();
        return $this->renderViewClient('about',[
            'categories' => $categories,
            'logo' => $logo,
            'slide_1' => $slide_1,
            'footer' => $footer,
            'hotline' => $hotline,
            'title_logo' => $title_logo,
        ]);
    }

    public function contact()
    {
        $webSetting = new WebSetting();
        $logo = $webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $slide_1 = $webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $webSetting->getByName('hotline')['value'] ?? '0901234567';
        $title_logo =  $webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        $categories = $this->category->getAll();
        return $this->renderViewClient('contact',[
            'categories' => $categories,
            'logo' => $logo,
            'slide_1' => $slide_1,
            'footer' => $footer,
            'hotline' => $hotline,
            'title_logo' => $title_logo,
        ]);
    }

}