<?php

// File dùng chung cho dự án 


namespace Lecon\Mvcoop\Commons;
use eftec\bladeone\BladeOne;

class Controller
{
    public function renderViewClient($view, $data = []) {
        $templatePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Client';
        $compilePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'compiles' . DIRECTORY_SEPARATOR . 'client';
        
        if (!is_dir($compilePath)) {
            mkdir($compilePath, 0777, true);
        }
        
        $blade = new BladeOne($templatePath, $compilePath, BladeOne::MODE_AUTO);
        echo $blade->run($view, $data);
    }

    public function renderViewAdmin($view, $data = []) {
        $templatePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Admin';
        $compilePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'compiles' . DIRECTORY_SEPARATOR . 'admin';
        
        if (!is_dir($compilePath)) {
            mkdir($compilePath, 0777, true);
        }
        
        $blade = new BladeOne($templatePath, $compilePath, BladeOne::MODE_AUTO);
        echo $blade->run($view, $data);
    }
}