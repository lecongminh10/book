<!-- ======= Header ======= -->
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="/" class="logo d-flex align-items-center">
            <img src="/{{ $logo ?? 'assets/client/assets/img/logo.png' }}" alt="Logo" style="max-height: 50px;">
            <h1>{{ $title_logo ?? 'ZenBlog' }}</h1>
        </a>

        @php
            $categories = (new \Lecon\Mvcoop\Models\Category)->getForMenu();
        @endphp

        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="/">Trang chủ</a></li>
                <li class="dropdown"><a href="#"><span>Danh mục</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        @foreach($categories as $category)
                            <li><a href="/categorise/{{ $category['id'] }}">{{ $category['name'] }}</a></li>
                        @endforeach
                    </ul>
                </li>
                <li><a href="/about">Về chúng tôi</a></li>
                <li><a href="/contact">Liên hệ</a></li>
            </ul>
        </nav><!-- .navbar -->

        <div class="position-relative">
            <a href="#" class="mx-2"><span class="bi-facebook"></span></a>
            <a href="#" class="mx-2"><span class="bi-twitter"></span></a>
            <a href="#" class="mx-2"><span class="bi-instagram"></span></a>
            <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
            <i class="bi bi-list mobile-nav-toggle"></i>

            <!-- ======= Search Form ======= -->
            <div class="search-form-wrap js-search-form-wrap">
                <form action="search-result.html" class="search-form">
                    <span class="icon bi-search"></span>
                    <input type="text" placeholder="Search" class="form-control">
                    <button class="btn js-search-close"><span class="bi-x"></span></button>
                </form>
            </div><!-- End Search Form -->
        </div>

        <div class="position-relative">
            @if(!isset($_SESSION['user']))
                <a href="/login" class="mx-2">Đăng nhập</a>
                <a href="/register" class="mx-2">Đăng ký</a>
            @else
                <!-- User dropdown menu -->
                <div class="dropdown d-inline">
                    <a class="dropdown-toggle mx-2" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @if(isset($_SESSION['user']['full_name']))
                            <i class="fas fa-user"></i> {{ $_SESSION['user']['full_name'] }}
                        @else
                            <i class="fas fa-user"></i> Người dùng
                        @endif
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="/profile"><i class="fas fa-user-cog"></i> Hồ sơ cá nhân</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</header><!-- End Header -->