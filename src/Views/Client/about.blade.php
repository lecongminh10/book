@extends('layouts.master')

@section('title')
    Về Cửa Hàng Sách Của Chúng Tôi
@endsection

@section('content')
<main id="main">
    <section>
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col-lg-12 text-center mb-5">
            <h1 class="page-title">Về Cửa Hàng Sách Của Chúng Tôi</h1>
          </div>
        </div>

        <div class="row mb-5">
          <div class="d-md-flex post-entry-2 half">
            <a href="#" class="me-4 thumbnail">
              <img src="./assets/client/assets/img/bookstore-shelf.jpg" alt="Nội thất cửa hàng sách ấm cúng" class="img-fluid">
            </a>
            <div class="ps-md-5 mt-4 mt-md-0">
              <div class="post-meta mt-4">Về Chúng Tôi</div>
              <h2 class="mb-4 display-4">Lịch Sử Cửa Hàng</h2>
              <p>Thành lập vào năm 2015, cửa hàng sách của chúng tôi bắt đầu như một góc nhỏ dành cho những người yêu sách trong một khu phố yên bình. Với niềm đam mê văn học, chúng tôi đã xây dựng một không gian nơi những câu chuyện trở nên sống động. Ngày nay, nền tảng trực tuyến của chúng tôi mang hàng ngàn cuốn sách—from tiểu thuyết kinh điển đến sách bán chạy hiện đại—đến với độc giả trên toàn thế giới.</p>
              <p>Hành trình của chúng tôi được dẫn dắt bởi tình yêu dành cho sách và cam kết xây dựng văn hóa đọc. Chúng tôi tự hào giới thiệu một bộ sưu tập đa dạng và cung cấp gợi ý cá nhân hóa để giúp mỗi độc giả tìm thấy cuốn sách tuyệt vời tiếp theo của họ.</p>
            </div>
          </div>

          <div class="d-md-flex post-entry-2 half mt-5">
            <a href="#" class="me-4 thumbnail order-2">
              <img src="./assets/client/assets/img/bookstack.jpg" alt="Hus sách" class="img-fluid">
            </a>
            <div class="pe-md-5 mt-4 mt-md-0">
              <div class="post-meta mt-4">Sứ Mệnh & Tầm Nhìn</div>
              <h2 class="mb-4 display-4">Sứ Mệnh & Tầm Nhìn</h2>
              <p>Sứ mệnh của chúng tôi là mang văn học đến gần hơn với mọi người, cung cấp một loạt sách đa dạng để khơi dậy sự tò mò và trí tưởng tượng. Chúng tôi hướng đến việc mang lại dịch vụ khách hàng xuất sắc, đảm bảo mỗi cuốn sách tìm được người đọc phù hợp. Tầm nhìn của chúng tôi là xây dựng một cộng đồng độc giả toàn cầu, cùng chia sẻ niềm đam mê với những câu chuyện và tri thức.</p>
              <p>Chúng tôi cam kết thúc đẩy việc đọc viết và hỗ trợ các tác giả từ mọi hoàn cảnh. Bằng cách lựa chọn kỹ lưỡng các đầu sách và đưa ra gợi ý phù hợp, chúng tôi hy vọng truyền cảm hứng cho thói quen đọc sách suốt đời và tạo ra những kết nối ý nghĩa thông qua sức mạnh của sách.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mb-5 bg-light py-5">
      <div class="container" data-aos="fade-up">
        <div class="row justify-content-between align-items-lg-center">
          <div class="col-lg-5 mb-4 mb-lg-0">
            <h2 class="display-4 mb-4">Tin Tức Mới Nhất</h2>
            <p>Hãy theo dõi những cập nhật mới nhất từ cửa hàng sách của chúng tôi! Từ các buổi ra mắt sách độc quyền, sự kiện ký tặng sách của tác giả, đến các chương trình đọc sách cộng đồng, chúng tôi luôn tìm cách để tôn vinh văn học. Blog của chúng tôi giới thiệu các bài đánh giá sách, mẹo đọc sách và thông tin về những nỗ lực của chúng tôi trong việc hỗ trợ thư viện địa phương và các chương trình khuyến đọc.</p>
            <p>Hãy tham gia cùng chúng tôi để khám phá những câu chuyện mới, gặp gỡ các tác giả yêu thích và trở thành một phần của cộng đồng yêu sách. Cùng nhau, chúng ta có thể lan tỏa niềm vui đọc sách đến mọi người!</p>
            <p><a href="/" class="more">Xem Tất Cả Bài Blog</a></p>
          </div>
          <div class="col-lg-6">
            <div class="row">
              <div class="col-6">
                <img src="./assets/client/assets/img/book-event.jpg" alt="Sự kiện đọc sách" class="img-fluid mb-4">
              </div>
              <div class="col-6 mt-4">
                <img src="./assets/client/assets/img/book-display.jpg" alt="Trưng bày sách" class="img-fluid mb-4">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
@endsection