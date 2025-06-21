<?php

namespace Lecon\Mvcoop\Controllers\Client;
use Lecon\Mvcoop\Commons\Controller;



use Lecon\Mvcoop\Models\Category;
use Lecon\Mvcoop\Models\Post;


class HomeController extends Controller
{
    

    private Category $category;
    public function __construct(){
        $this -> category = new Category;
    }
    public function index()
    {
    
        // Lấy danh sách tất cả các category
        $categories = $this->category->getAll();
    
        // Lấy danh sách 3 bài viết hàng đầu
       // $postTop3 = $this->post->Top3FirsPost();

       // Lấy ra title đứng số 1 theo category
       $postFirstLatestTitle=[];
       // Lấy ra title đứng số 1 theo category 5
        $poststitileTradding= [];

        $postsByCategory = [];
        $postsByCategory2 = [];
        foreach ($categories as $category) {

        }

       

        // Trả về view với dữ liệu được truyền đi
        return $this->renderViewClient(
            'home',
            [
                'categories' => $categories,
                'postsByCategory' => $postsByCategory,
                'postsByCategory2' => $postsByCategory2,
                'poststitileTradding'=> $poststitileTradding,
                'postFirstLatestTitle'=>$postFirstLatestTitle
            ]
        );
    }
    

    public function categories(){
        $categories = new Category; // Khởi tạo đối tượng Category
        $categories->getAll();
        return $this -> renderViewClient('home',$categories);
    }
}