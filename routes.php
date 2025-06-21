<?php
// Định nghĩa đường dẫn 


use Bramus\Router\Router;
use Lecon\Mvcoop\Controllers\Admin\AuthenticateController;
use Lecon\Mvcoop\Controllers\Admin\CategoryController;
use Lecon\Mvcoop\Controllers\Admin\DashboardController;
use Lecon\Mvcoop\Controllers\Admin\PostsController;
use Lecon\Mvcoop\Controllers\Admin\UserController;
use Lecon\Mvcoop\Controllers\Admin\BorrowingController;
use Lecon\Mvcoop\Controllers\Client\HomeController;
use Lecon\Mvcoop\Controllers\Client\PostController as ClientPostController;
use Lecon\Mvcoop\Controllers\Client\BorrowController;
use Lecon\Mvcoop\Controllers\Admin\BookController;

// Create Router instance
$router = new Router();

// Define routes
// ...


// Tạo một tuyến đường (route) trong Laravel
$router->get("/", HomeController::class . '@index');


$router-> match('GET|POST' , '/auth/login' , AuthenticateController::class . '@login');

// Book borrowing routes
$router->match('GET|POST', '/borrow', BorrowController::class . '@borrowBook');
$router->get('/my-borrowings', BorrowController::class . '@myBorrowings');
$router->get('/borrow/cancel/{id}', BorrowController::class . '@cancelBorrowing');

$router->mount("/admin", function () use ($router){
   
    $router -> get("/" , DashboardController ::class ."@index");
    $router ->get('/logout' ,   AuthenticateController::class. '@logout');

    // Book borrowing management routes
    $router->mount('/borrowings', function () use ($router) {
        $router->get('/', BorrowingController::class . '@index');
        $router->get('/pending', BorrowingController::class . '@pending');
        $router->get('/active', BorrowingController::class . '@active');
        $router->get('/overdue', BorrowingController::class . '@overdue');
        $router->get('/statistics', BorrowingController::class . '@statistics');
        $router->get('/approve/{id}', BorrowingController::class . '@approve');
        $router->get('/reject/{id}', BorrowingController::class . '@reject');
        $router->get('/return/{id}', BorrowingController::class . '@return');
    });


    $router->mount('/books', function () use ($router) {
        $router->get('/', BookController::class . '@index');
        $router->match('GET|POST', '/create',       BookController::class . '@create');
    });
    $router->mount('/shelves', function () use ($router) {
        $router->get('/', BookController::class . '@index');
        $router->match('GET|POST', '/create',       BookController::class . '@create');
    });


    $router->mount('/users', function () use ($router) {
        $router->get('/',                           UserController::class . '@index');
        $router->get('/{id}/show',                  UserController::class . '@show');
        $router->get('/{id}/delete',                UserController::class . '@delete');
        $router->match('GET|POST', '/{id}/update',  UserController::class . '@update');
        $router->match('GET|POST', '/create',       UserController::class . '@create');
    });
    $router->mount('/categorys', function () use ($router) {
        $router->get('/',                          CategoryController::class . '@index');
        $router->get('/{id}/show',                 CategoryController::class . '@show');
        $router->get('/{id}/delete',               CategoryController::class . '@delete');
        $router->match('GET|POST', '/{id}/update',  CategoryController::class . '@update');
        $router->match('GET|POST', '/create',       CategoryController::class . '@create');
    });


});

$router->before('GET|POST', '/admin/*', function() {
    if (!isset($_SESSION['user'])) {
        header('Location: /auth/login');
        exit();
    }
});




// Giải thích từng phần:
// - $router: Đối tượng định tuyến trong Laravel, sử dụng để định rõ các tuyến của ứng dụng.
// - get("/"): Tạo một tuyến với phương thức HTTP là GET và đường dẫn là "/" (đường dẫn gốc).

// Xác định controller và phương thức để xử lý khi tuyến được truy cập.
// - HomeController::class: Tên của controller được sử dụng.
// - '@index': Phương thức trong controller (trong trường hợp này, là phương thức "index").
//   Controller và phương thức này sẽ được gọi khi tuyến được truy cập.

// Run it!
$router->run();