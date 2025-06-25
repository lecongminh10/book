@extends('layouts.master')

@section('title')
    Cập nhật Sách
@endsection

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 form-card">
        <h2 class="mb-4 text-center fw-bold">Cập nhật Sách</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề sách</label>
                        <input type="text" class="form-control custom-input" id="title" name="title" value="{{ $book['title'] ?? '' }}" required placeholder="Nhập tiêu đề sách">
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Tác giả</label>
                        <select class="form-select custom-select" id="author" name="author" required>
                            <option value="">-- Chọn tác giả --</option>
                            @foreach($authors as $author)
                                <option value="{{ $author['full_name'] }}" @if(($book['author'] ?? '') == $author['full_name']) selected @endif>{{ $author['full_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Thể loại</label>
                        <select class="form-select custom-select" id="category_id" name="category_id" required>
                            <option value="">-- Chọn thể loại --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['id'] }}" @if(($book['category_id'] ?? '') == $category['id']) selected @endif>{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control custom-input" id="isbn" name="isbn" value="{{ $book['isbn'] ?? '' }}" placeholder="Nhập ISBN">
                    </div>
                    <div class="mb-3">
                        <label for="is_featured" class="form-label">Flash</label>
                        <select class="form-select custom-select" id="is_featured" name="is_featured" required>
                            <option value="">--Nội bật --</option>
                            <option value="1" @if(($book['is_featured'] ?? 0) == 1) selected @endif>--Hiển thị nỗi bật --</option>
                            <option value="0" @if(($book['is_featured'] ?? 0) == 0) selected @endif>--Không hiển thị nỗi bật --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cover_front" class="form-label">Bìa trước</label>
                        <div class="input-group">
                            <input type="file" class="form-control file-input" id="cover_front" name="cover_front" accept="image/*">
                            @if(!empty($book['cover_front']))
                                <button type="button" class="btn btn-outline-primary ms-2" id="view-cover-front">Xem ảnh</button>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cover_back" class="form-label">Bìa sau</label>
                        <div class="input-group">
                            <input type="file" class="form-control file-input" id="cover_back" name="cover_back" accept="image/*">
                            @if(!empty($book['cover_back']))
                                <button type="button" class="btn btn-outline-primary ms-2" id="view-cover-back">Xem ảnh</button>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="location_description" class="form-label">Kệ sách</label>
                        <select class="form-select custom-select" id="location_description" name="location_description" required>
                            <option value="">-- Chọn kệ --</option>
                            @foreach($shelves as $shelf)
                                <option value="{{ $shelf['id'] }}" data-name="{{ $shelf['name'] }}" @if(($book['location_description'] ?? '') == $shelf['name']) selected @endif>{{ $shelf['name']}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" id="location_description_name" name="location_description_name" value="{{ $book['location_description'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="shelf-position" class="form-label">Vị trí trong kệ</label>
                        <select class="form-select custom-select" id="shelf-position" name="shelf_position_id" required @if(empty($book['shelf_position_id'])) disabled @endif value="{{ $book['shelf_position_id'] }}">
                            <option value="">-- Chọn vị trí --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="publish_year" class="form-label">Năm xuất bản</label>
                        <input type="number" class="form-control custom-input" id="publish_year" name="publish_year" value="{{ $book['publish_year'] ?? '' }}" placeholder="Nhập năm xuất bản" min="1900" max="2025">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="summary" class="form-label">Tóm tắt</label>
                        <textarea class="form-control custom-input" id="summary" name="summary" rows="3" placeholder="Nhập tóm tắt sách">{{ $book['summary'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung</label>
                        <textarea class="form-control custom-input" id="editor-container" name="content" rows="5" placeholder="Nhập nội dung sách">{{ $book['content'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-gradient w-100 fw-bold py-2">Lưu thay đổi</button>
                </div>
                <div class="col-md-2 mb-2">
                    <a href="/admin/books" class="btn btn-secondary w-100 fw-bold py-2">Quay lại</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal hiển thị ảnh -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Xem ảnh</h5>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Ảnh lớn" style="max-width:100%;max-height:70vh;">
      </div>
    </div>
  </div>
</div>
<style>
    .cke_notifications_area{
        display: none;
    }
</style>
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('summary');
    CKEDITOR.replace('editor-container');
    document.addEventListener('DOMContentLoaded', function() {
        var shelfSelect = document.getElementById('location_description');
        var positionSelect = document.getElementById('shelf-position');
        var currentShelfId = shelfSelect.value;
        var currentPositionID = {{$book['shelf_position_id']}}
        // Nếu đã có vị trí, load vị trí cho kệ hiện tại
        if (currentShelfId) {
            fetch('/admin/shelves/' + currentShelfId + '/positions')
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">-- Chọn vị trí --</option>';
                    data.forEach(function(pos) {
                        html += `<option value="${pos.id}" ${pos.id == currentPositionID ? 'selected' : ''}>${pos.title} (Vị trí: ${pos.position_x+1},${pos.position_y+1})</option>`;
                    });
                    positionSelect.innerHTML = html;
                    positionSelect.disabled = false;
                });
        }
        shelfSelect.addEventListener('change', function() {
            var shelfId = this.value;
            var shelfName = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
            document.getElementById('location_description_name').value = shelfName;
            positionSelect.innerHTML = '<option value="">Đang tải...</option>';
            positionSelect.disabled = true;
            if (!shelfId) {
                positionSelect.innerHTML = '<option value="">-- Chọn vị trí --</option>';
                positionSelect.disabled = true;
                return;
            }
            fetch('/admin/shelves/' + shelfId + '/positions')
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">-- Chọn vị trí --</option>';
                    data.forEach(function(pos) {
                        html += `<option value="${pos.id}">${pos.title} (Vị trí: ${pos.position_x+1},${pos.position_y+1})</option>`;
                    });
                    positionSelect.innerHTML = html;
                    positionSelect.disabled = false;
                })
                .catch(() => {
                    positionSelect.innerHTML = '<option value="">Không lấy được vị trí</option>';
                    positionSelect.disabled = true;
                });
        });
        // Xem ảnh popup
        var viewFrontBtn = document.getElementById('view-cover-front');
        var viewBackBtn = document.getElementById('view-cover-back');
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        var modalImg = document.getElementById('modalImage');
        if (viewFrontBtn) {
            viewFrontBtn.addEventListener('click', function() {
                modalImg.src = '/{{ $book['cover_front'] }}';
                modal.show();
            });
        }
        if (viewBackBtn) {
            viewBackBtn.addEventListener('click', function() {
                modalImg.src = '/{{ $book['cover_back'] }}';
                modal.show();
            });
        }
    });
    var modal = new bootstrap.Modal(document.getElementById('imageModal'), {
       backdrop: true, // cho phép click ra ngoài để đóng
       keyboard: true  // cho phép nhấn ESC để đóng
    });
</script>
@endsection
