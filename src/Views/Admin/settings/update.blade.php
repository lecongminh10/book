@extends('layouts.master')

@section('title')
    Cập nhật Thiết lập
@endsection

@section('content')
<div class="settings-container">
    <div class="settings-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-cog"></i>
                Cập nhật Thiết lập
            </h2>
        </div>
        
        <div class="card-body">
            @if(!empty($_SESSION['success']))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ $_SESSION['success'] }}
                </div>
                @php
                    $_SESSION['success'] = null;
                @endphp
            @endif

            @if(!empty($_SESSION['error']))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $_SESSION['error'] }}
                </div>
                @php
                    $_SESSION['error'] = null;
                @endphp
            @endif

            <form action="/admin/settings/update/{{ $setting['id'] }}" method="POST" enctype="multipart/form-data" class="settings-form">
                @csrf
                <input type="hidden" name="id" value="{{ $setting['id'] }}">
                
                <div class="form-group">
                    <label for="title" class="form-label">
                        <i class="fas fa-heading"></i>
                        Tiêu đề
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="title" 
                           name="title" 
                           value="{{ $setting['title'] }}" 
                           placeholder="Nhập tiêu đề..."
                           required>
                </div>

                @if($setting['name'] === 'logo')
                    <!-- Logo Name Field -->
                    <div class="form-group">
                        <label for="logo_name" class="form-label">
                            <i class="fas fa-tag"></i>
                            Tên Logo
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="logo_name" 
                               name="logo_name" 
                               value="{{ $setting['logo_name'] ?? 'ZenBlog' }}" 
                               placeholder="Nhập tên logo...">
                    </div>

                    <!-- Logo Image Upload -->
                    <div class="form-group image-upload-group">
                        <label for="images" class="form-label">
                            <i class="fas fa-image"></i>
                            Tải lên logo mới
                        </label>
                        
                        <div class="logo-upload-container">
                            <div class="current-image-preview" id="currentImagePreview">
                                @if($setting['value'] && file_exists('N:/laragon/www/book/' . $setting['value']))
                                    <img src="/{{ $setting['value'] }}?v={{ time() }}" 
                                         alt="{{ $setting['name'] }}" 
                                         id="currentImage"
                                         class="preview-image logo-preview">
                                    <div class="image-overlay">
                                        <span class="image-label">Logo hiện tại</span>
                                    </div>
                                @else
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-image"></i>
                                        <span>Chưa có logo</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="file-input-wrapper">
                                <input type="file" 
                                       class="form-control file-input" 
                                       id="images" 
                                       name="images[]" 
                                       accept="image/*"
                                       onchange="previewImages(this, 'logo')">
                                <label for="images" class="file-input-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Chọn logo mới</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="new-images-preview" id="newImagesPreview"></div>
                    </div>

                @elseif(str_contains($setting['name'], 'slide'))
                    <!-- Slide Images Upload -->
                    <div class="form-group image-upload-group">
                        <label for="images" class="form-label">
                            <i class="fas fa-images"></i>
                            Tải lên ảnh slide
                        </label>
                        
                        <div class="slides-container">
                            <div class="current-slides-preview">
                                <h4 class="section-title">
                                    <i class="fas fa-eye"></i>
                                    Ảnh slide hiện tại
                                </h4>
                                <div class="slides-grid" id="currentSlidesGrid">
                                    @if($setting['value'])
                                        @php
                                            $slides = explode(',', $setting['value']);
                                        @endphp
                                        @foreach($slides as $index => $slide)
                                            @php $slide = trim($slide); @endphp
                                            @if($slide && file_exists('N:/laragon/www/book/' . $slide))
                                                <div class="slide-item">
                                                    <img src="/{{ $slide }}?v={{ time() }}" 
                                                         alt="Slide {{ $index + 1 }}" 
                                                         class="slide-image">
                                                    <div class="slide-overlay">
                                                        <span class="slide-number">{{ $index + 1 }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="no-slides-placeholder">
                                            <i class="fas fa-images"></i>
                                            <span>Chưa có slide nào</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="upload-section">
                                <div class="file-input-wrapper multiple-files">
                                    <input type="file" 
                                           class="form-control file-input" 
                                           id="images" 
                                           name="images[]" 
                                           accept="image/*"
                                           multiple
                                           onchange="previewImages(this, 'slides')">
                                    <label for="images" class="file-input-label multiple-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Chọn nhiều ảnh slide</span>
                                        <small>Có thể chọn nhiều ảnh cùng lúc</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="new-images-preview" id="newImagesPreview"></div>
                    </div>

                @else
                    <!-- Regular Text Field -->
                    <div class="form-group">
                        <label for="value" class="form-label">
                            <i class="fas fa-edit"></i>
                            Giá trị
                        </label>
                        <textarea class="form-control textarea-field" 
                                  id="value" 
                                  name="value" 
                                  rows="4"
                                  placeholder="Nhập giá trị..."
                                  required>{{ $setting['value'] }}</textarea>
                    </div>
                @endif
                
                @if(in_array($setting['name'], ['logo']) || str_contains($setting['name'], 'slide'))
                    <div class="upload-info">
                        <div class="info-card">
                            <i class="fas fa-info-circle"></i>
                            <div class="info-content">
                                <strong>Thông tin upload:</strong>
                                <ul>
                                    <li>Định dạng: JPEG, PNG, GIF, WebP</li>
                                    <li>Kích thước tối đa: 5MB mỗi file</li>
                                    @if(str_contains($setting['name'], 'slide'))
                                        <li>Có thể chọn nhiều ảnh cùng lúc</li>
                                        <li>Ảnh sẽ được phân cách bằng dấu phẩy</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Cập nhật
                    </button>
                    <a href="/admin/settings" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImages(input, type) {
    const files = input.files;
    const newImagesPreview = document.getElementById('newImagesPreview');
    
    if (files.length === 0) return;
    
    // Clear previous previews
    newImagesPreview.innerHTML = '';
    
    // Validate each file
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('File ' + file.name + ' không phải là ảnh hợp lệ');
            input.value = '';
            return;
        }
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File ' + file.name + ' vượt quá 5MB');
            input.value = '';
            return;
        }
    }
    
    // Create preview container
    const previewContainer = document.createElement('div');
    previewContainer.className = type === 'logo' ? 'new-logo-preview' : 'new-slides-preview';
    
    if (type === 'slides') {
        const title = document.createElement('h4');
        title.className = 'section-title';
        title.innerHTML = '<i class="fas fa-plus-circle"></i> Ảnh slide mới';
        previewContainer.appendChild(title);
        
        const grid = document.createElement('div');
        grid.className = 'slides-grid';
        previewContainer.appendChild(grid);
        
        // Process each file
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const slideItem = document.createElement('div');
                slideItem.className = 'slide-item new-slide';
                slideItem.innerHTML = `
                    <img src="${e.target.result}" alt="New slide ${i + 1}" class="slide-image">
                    <div class="slide-overlay">
                        <span class="slide-number">${i + 1}</span>
                        <button type="button" class="remove-slide" onclick="removeSlide(this, ${i})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                grid.appendChild(slideItem);
            };
            
            reader.readAsDataURL(file);
        }
    } else {
        // Logo preview
        const file = files[0]; // Only first file for logo
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewContainer.className = 'new-logo-preview';
            previewContainer.innerHTML = `
                <div class="logo-preview-item">
                    <img src="${e.target.result}" alt="New logo" class="preview-image logo-preview">
                    <div class="image-overlay">
                        <span class="image-label">Logo mới</span>
                        <button type="button" class="remove-new-image" onclick="removeLogo()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
        };
        
        reader.readAsDataURL(file);
    }
    
    newImagesPreview.appendChild(previewContainer);
    
    // Add animation
    setTimeout(() => {
        previewContainer.style.opacity = '1';
        previewContainer.style.transform = 'translateY(0)';
    }, 100);
}

function removeSlide(button, index) {
    const slideItem = button.closest('.slide-item');
    slideItem.remove();
    
    // Update file input (remove specific file)
    const fileInput = document.getElementById('images');
    const dt = new DataTransfer();
    const files = fileInput.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    fileInput.files = dt.files;
    
    // Update slide numbers
    const slideItems = document.querySelectorAll('.new-slide .slide-number');
    slideItems.forEach((item, idx) => {
        item.textContent = idx + 1;
    });
}

function removeLogo() {
    const fileInput = document.getElementById('images');
    const newImagesPreview = document.getElementById('newImagesPreview');
    
    fileInput.value = '';
    newImagesPreview.innerHTML = '';
}

// Form validation
document.querySelector('.settings-form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('Vui lòng nhập tiêu đề');
        document.getElementById('title').focus();
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
    submitBtn.disabled = true;
    
    // Re-enable after 10 seconds as fallback
    setTimeout(() => {
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
    }, 10000);
});
</script>
@endsection

@section('styles')
<style>
/* Container and Layout */
.settings-container {
    min-height: 100vh;
    background: #f8fafc;
    padding: 2rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.settings-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    max-width: 1000px;
    width: 100%;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

/* Header */
.card-header {
    background: #1f2937;
    text-align: center;
}

.card-title {
    color: white;
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.card-title i {
    font-size: 1.5rem;
}

/* Card Body */
.card-body {
    padding: 2rem;
}

/* Alerts */
.alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

/* Form Styling */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-label i {
    color: #6b7280;
    width: 16px;
}

/* Input Styling */
.form-control {
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.textarea-field {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    line-height: 1.5;
}

/* Image Upload Styling */
.image-upload-group {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

/* Logo Upload */
.logo-upload-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin: 1rem 0;
}

.current-image-preview {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    position: relative;
    min-height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    max-width: 100%;
    max-height: 140px;
    object-fit: contain;
}

.logo-preview {
    max-height: 100px;
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.5rem;
    text-align: center;
    font-weight: 500;
    font-size: 0.75rem;
    text-transform: uppercase;
}

.no-image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-weight: 500;
}

.no-image-placeholder i {
    font-size: 2rem;
    color: #d1d5db;
}

/* Slides Container */
.slides-container {
    position: relative;
}

.current-slides-preview,
.new-slides-preview {
    margin: 1rem 0;
}

.section-title {
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.section-title i {
    color: #6b7280;
}

.slides-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.slide-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    border: 1px solid #e5e7eb;
    aspect-ratio: 16/10;
    transition: all 0.2s ease;
}

.slide-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
}

.slide-item.new-slide {
    border-color: #10b981;
}

.slide-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 0.75rem;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.slide-item:hover .slide-overlay {
    opacity: 1;
}

.slide-number {
    background: #374151;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
}

.remove-slide {
    background: #ef4444;
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    transition: background-color 0.2s ease;
}

.remove-slide:hover {
    background: #dc2626;
}

.remove-new-image {
    background: #ef4444;
    color: white;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.75rem;
    transition: background-color 0.2s ease;
}

.remove-new-image:hover {
    background: #dc2626;
}

.no-slides-placeholder {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    padding: 3rem 1rem;
    background: white;
    border-radius: 8px;
    border: 1px dashed #d1d5db;
}

.no-slides-placeholder i {
    font-size: 2.5rem;
    color: #d1d5db;
}

/* File Input */
.file-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-input-label {
    background: #3b82f6;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    text-align: center;
    transition: background-color 0.2s ease;
    min-height: 120px;
    justify-content: center;
    width: 100%;
}

.file-input-label:hover {
    background: #2563eb;
}

.file-input-label i {
    font-size: 1.5rem;
}

.file-input-label small {
    font-size: 0.75rem;
    opacity: 0.9;
    font-weight: 400;
}

.multiple-label {
    background: #10b981;
}

.multiple-label:hover {
    background: #059669;
}

/* Preview Styling */
.new-logo-preview,
.new-slides-preview {
    margin-top: 1rem;
}

.logo-preview-item {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #10b981;
    position: relative;
}

/* Upload Info */
.upload-info {
    margin-top: 1.5rem;
}

.info-card {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}

.info-card i {
    color: #3b82f6;
    font-size: 1.125rem;
    margin-top: 0.125rem;
}

.info-content {
    flex: 1;
}

.info-content strong {
    color: #1e40af;
    font-size: 0.875rem;
    display: block;
    margin-bottom: 0.5rem;
}

.info-content ul {
    margin: 0;
    padding-left: 1rem;
    color: #374151;
    font-size: 0.875rem;
}

.info-content li {
    margin-bottom: 0.25rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn i {
    font-size: 1rem;
}

/* Upload Section */
.upload-section {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px dashed #d1d5db;
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-container {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .logo-upload-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .slides-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .card-title {
        font-size: 1.5rem;
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
@endsection