<?php
namespace Lecon\Mvcoop\Controllers\Admin;
use Lecon\Mvcoop\Commons\Controller;

class DashboardController extends Controller
{
    public function index(){
        return $this -> renderViewAdmin('dashboard');
    }

}