<?php

namespace Lecon\Mvcoop\Controllers\Client;
use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\WebSetting;

class WebSettingController extends Controller
{
    private WebSetting $webSetting;

    public function __construct()
    {
        $this->webSetting = new WebSetting();
    }

    public function index()
    {
        $logo = $this->webSetting->getByName('logo')['value'] ?? '/assets/client/assets/img/logo.png';
        $title_logo = $this->webSetting->getByName('title_logo')['value'] ?? 'ZEN BLOG';
        $slide_1 = $this->webSetting->getByName('slide_1')['value'] ?? '/assets/client/assets/img/slide1.jpg';
        $footer = $this->webSetting->getByName('footer')['value'] ?? '© 2025 ZenBlog. All rights reserved.';
        $hotline = $this->webSetting->getByName('hotline')['value'] ?? '0901234567';

        return $this->renderViewClient(
            'home',
            [
                'logo' => $logo,
                'slide_1' => $slide_1,
                'footer' => $footer,
                'hotline' => $hotline,
                'title_logo' => $title_logo,
            ]
        );
    }
}