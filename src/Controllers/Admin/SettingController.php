<?php

namespace Lecon\Mvcoop\Controllers\Admin;

use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\WebSetting;

class SettingController extends Controller
{
    private WebSetting $webSetting;
    private string $folder = 'settings.';
    private string $uploadDir = 'uploads/settings/'; // Relative to project root
    private string $basePath = __DIR__ . '/../../../'; // Project root (N:\laragon\www\book\)

    public function __construct()
    {
        $this->webSetting = new WebSetting();
    }

    // Danh sách
    public function index()
    {
        $data['settings'] = $this->webSetting->getAll();
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    // Thêm mới
    public function create()
    {
        if (!empty($_POST)) {
            $title = $_POST['title'] ?? null;
            $name = $_POST['name'] ?? null;
            $value = $_POST['value'] ?? null;

            // Kiem tra trung lap name
            if ($this->webSetting->getByName($name)) {
                return $this->renderViewAdmin($this->folder . 'create', ['error' => 'Ten thiet lap da ton tai.']);
            }

            $this->webSetting->insertFull($title, $name, $value);
            header('Location: /admin/settings');
            exit();
        }
        return $this->renderViewAdmin($this->folder . 'create');
    }

    // Xem chi tiết theo ID
    public function show($id)
    {
        $data['setting'] = $this->webSetting->getById($id);

        if (empty($data['setting'])) {
            die(404);
        }

        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    // Cập nhật theo ID
    public function update($id)
    {
        $data['setting'] = $this->webSetting->getById($id);

        if (empty($data['setting'])) {
            die(404);
        }

        if (!empty($_POST)) {
            $title = $_POST['title'] ?? null;
            $value = $_POST['value'] ?? null;
            $images = $_FILES['images'] ?? null;
            $logoName = $_POST['logo_name'] ?? null; // New logo name field
            $oldValue = $data['setting']['value']; // Store old value
            $settingName = $data['setting']['name'];

            // Handle logo name update for logo setting
            if ($settingName === 'logo' && $logoName) {
                $data['setting']['logo_name'] = $logoName; // Store for view
            }

            // Handle multiple image uploads for slides or single image for logo
            if ($images && !empty($images['name'][0])) {
                $uploadedPaths = [];
                $imageCount = count($images['name']);
                
                for ($i = 0; $i < $imageCount; $i++) {
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        // Validate image type
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $images['tmp_name'][$i]);
                        finfo_close($finfo);
                        
                        if (!in_array($mimeType, $allowedTypes)) {
                            $_SESSION['error'] = 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)';
                            header('Location: /admin/settings/update/' . $id);
                            exit();
                        }

                        // Validate file size (max 5MB)
                        if ($images['size'][$i] > 5 * 1024 * 1024) {
                            $_SESSION['error'] = 'Kích thước file không được vượt quá 5MB';
                            header('Location: /admin/settings/update/' . $id);
                            exit();
                        }

                        // Generate unique filename
                        $fileExtension = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
                        $fileName = uniqid() . '_' . time() . '_' . $i . '.' . $fileExtension;
                        $uploadPath = $this->uploadDir . $fileName;
                        $fullPath = $this->basePath . $uploadPath;

                        // Ensure upload directory exists
                        $dirPath = $this->basePath . $this->uploadDir;
                        if (!file_exists($dirPath)) {
                            mkdir($dirPath, 0777, true);
                        }

                        // Upload image
                        if (is_writable($dirPath)) {
                            if (move_uploaded_file($images['tmp_name'][$i], $fullPath)) {
                                $uploadedPaths[] = $uploadPath;
                            } else {
                                $_SESSION['error'] = 'Lỗi khi tải ảnh lên. Vui lòng thử lại.';
                                header('Location: /admin/settings/update/' . $id);
                                exit();
                            }
                        } else {
                            $_SESSION['error'] = 'Thư mục upload không có quyền ghi.';
                            header('Location: /admin/settings/update/' . $id);
                            exit();
                        }
                    }
                }

                if (!empty($uploadedPaths)) {
                    // Delete old images
                    if ($oldValue) {
                        if (str_contains($oldValue, ',')) {
                            // Multiple images (slides)
                            $oldPaths = explode(',', $oldValue);
                            foreach ($oldPaths as $oldPath) {
                                $oldPath = trim($oldPath);
                                if ($oldPath && file_exists($this->basePath . $oldPath)) {
                                    unlink($this->basePath . $oldPath);
                                }
                            }
                        } else {
                            // Single image (logo)
                            if (file_exists($this->basePath . $oldValue)) {
                                unlink($this->basePath . $oldValue);
                            }
                        }
                    }

                    // Set new value
                    if ($settingName === 'logo') {
                        // For logo, take only the first image
                        $value = $uploadedPaths[0];
                    } else {
                        // For slides, join with comma
                        $value = implode(',', $uploadedPaths);
                    }
                }
            }

            $this->webSetting->update($id, $title, $value);
            $_SESSION['success'] = 'Cập nhật thành công!';
            header('Location: /admin/settings');
            exit();
        }

        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    // Delete theo ID
    public function delete($id)
    {
        // Get setting info before deleting to remove image file
        $setting = $this->webSetting->getById($id);
        
        // Delete associated image file if exists
        if ($setting && $setting['value'] && in_array($setting['name'], ['logo', 'slide_1'])) {
            $imagePath = $this->basePath . $setting['value'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $this->webSetting->deleteById($id);
        $_SESSION['success'] = 'Xóa thành công!';
        header('Location: /admin/settings');
        exit();
    }
}