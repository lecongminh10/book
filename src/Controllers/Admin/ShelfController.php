<?php

namespace Lecon\Mvcoop\Controllers\Admin;
use Lecon\Mvcoop\Commons\Controller;
use Lecon\Mvcoop\Models\Shelf;

class ShelfController extends Controller {
    private string $folder = 'shelves.';
    private $shelfModel ;

    public function __construct() {
        $this->shelfModel= new Shelf();
    }

    public function index() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 5;
        $offset = ($page - 1) * $perPage;
        $total = $this->shelfModel->countShelves();
        $totalPages = ceil($total / $perPage);
        $data['shelves'] = $this->shelfModel->getShelvesPaginated($perPage, $offset);
        $data['pagination'] = [
            'current' => $page,
            'total' => $totalPages,
            'perPage' => $perPage,
            'totalItems' => $total
        ];
        return $this->renderViewAdmin(
            $this->folder . __FUNCTION__,
            $data
        );
    }

    public function create(){         
        $errors = [];         
        if (!empty($_POST)) {             
            $name = $_POST['name'] ?? '';             
            $location_note = $_POST['location_note'] ?? '';             
            $cols = isset($_POST['shelf-cols']) ? (int)$_POST['shelf-cols'] : 1;             
            $rows = isset($_POST['shelf-rows']) ? (int)$_POST['shelf-rows'] : 1;             
            $positions = $_POST['positions'] ?? [];              
            
            // Validate             
            if (empty($name)) {                 
                $errors['name'] = 'Tên kệ không được để trống';             
            }             
            if (count($positions) == 0) {                 
                $errors['positions'] = 'Vui lòng thêm ít nhất một vị trí sách!';             
            }
            
            // Validate số lượng positions không vượt quá kích thước kệ
            if (count($positions) > ($cols * $rows)) {
                $errors['positions'] = 'Số vị trí vượt quá cấu hình kệ!';
            }
            
            if (empty($errors)) {                 
                try {
                    // Bắt đầu transaction
                    $conn = $this->shelfModel->getConnection();
                    $conn->beginTransaction();
                    
                    // Lưu thông tin kệ
                    $sql = "INSERT INTO shelves (name, location_note) VALUES (?, ?)";                 
                    $stmt = $conn->prepare($sql);                 
                    $stmt->execute([$name, $location_note]);                 
                    $shelf_id = $conn->lastInsertId();                  
                    
                    // Parse và chuẩn bị dữ liệu positions
                    $parsed_positions = [];                 
                    foreach ($positions as $index => $pos) {                     
                        $parts = explode('||', $pos);                     
                        $title = trim($parts[0] ?? '');                     
                        $color = $parts[1] ?? '#FFD700';
                        
                        // Tính toán vị trí x, y dựa trên index và số cột
                        $position_x = $index % $cols;                     
                        $position_y = floor($index / $cols);
                        
                        // Validate title không để trống
                        if (!empty($title)) {
                            $parsed_positions[] = [                         
                                'shelf_id' => $shelf_id,
                                'title' => $title,                         
                                'color' => $color,                         
                                'position_x' => $position_x,                         
                                'position_y' => $position_y                     
                            ];
                        }
                    }                 
                    
                    // Lưu các vị trí sách
                    if (!empty($parsed_positions)) {
                        $sql = "INSERT INTO shelf_positions (shelf_id, title, color, position_x, position_y) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        
                        foreach ($parsed_positions as $position) {
                            $stmt->execute([
                                $position['shelf_id'],
                                $position['title'],
                                $position['color'],
                                $position['position_x'],
                                $position['position_y']
                            ]);
                        }
                    }
                    
                    // Commit transaction
                    $conn->commit();
                    
                    // Redirect về trang danh sách kệ
                    header('Location: /admin/shelves');                 
                    exit();
                    
                } catch (\Exception $e) {
                    // Rollback nếu có lỗi
                    $conn->rollback();
                    $errors['general'] = 'Có lỗi xảy ra khi lưu dữ liệu: ' . $e->getMessage();
                }
            }
            
            // Nếu có lỗi, render lại view và truyền lỗi                
            $data = [                     
                'errors' => $errors,                     
                'old' => $_POST                 
            ];                 
            return $this->renderViewAdmin(                     
                $this->folder . __FUNCTION__,                     
                $data                 
            );
        }         
        
        // Render view tạo mới kệ
        return $this->renderViewAdmin(             
            $this->folder . __FUNCTION__         
        );     
    }

    public function delete($id)
    {
        $this->shelfModel->deleteShelfAndPositions($id);
        header('Location:/admin/shelves/');
        exit();
    }

    public function update($id){
        $errors = [];
        $shelf = $this->shelfModel->findById($id);
        $shelfPositionModel = new \Lecon\Mvcoop\Models\ShelfPosition();
        $cols = 5;
        $rows = 2;
        if (!$shelf) {
            header('Location: /admin/shelves');
            exit();
        }
        $positions = $shelfPositionModel->getByShelfId($id); // lấy đầy đủ x, y, title, color
        if (!empty($positions)) {
            // Tìm số cột, số hàng lớn nhất từ dữ liệu vị trí (nếu không có dữ liệu cũ từ POST)
            $maxX = max(array_column($positions, 'position_x'));
            $maxY = max(array_column($positions, 'position_y'));
            $cols = $maxX + 1;
            $rows = $maxY + 1;
        }
        if (!empty($_POST)) {
            $name = $_POST['name'] ?? '';
            $location_note = $_POST['location_note'] ?? '';
            $cols = isset($_POST['shelf-cols']) ? (int)$_POST['shelf-cols'] : 1;
            $rows = isset($_POST['shelf-rows']) ? (int)$_POST['shelf-rows'] : 1;
            $positions_input = $_POST['positions'] ?? [];

            // Validate
            if (empty($name)) {
                $errors['name'] = 'Tên kệ không được để trống';
            }
            if (count($positions_input) == 0) {
                $errors['positions'] = 'Vui lòng thêm ít nhất một vị trí sách!';
            }
            if (count($positions_input) > ($cols * $rows)) {
                $errors['positions'] = 'Số vị trí vượt quá cấu hình kệ!';
            }

            if (empty($errors)) {
                try {
                    // Cập nhật thông tin kệ
                    $this->shelfModel->updateShelf($id, $name, $location_note);

                    // Parse và chuẩn bị dữ liệu positions
                    $parsed_positions = [];
                    foreach ($positions_input as $index => $pos) {
                        $parts = explode('||', $pos);
                        $title = trim($parts[0] ?? '');
                        $color = $parts[1] ?? '#FFD700';
                        $position_x = $index % $cols;
                        $position_y = floor($index / $cols);
                        if (!empty($title)) {
                            $parsed_positions[] = [
                                'title' => $title,
                                'color' => $color,
                                'position_x' => $position_x,
                                'position_y' => $position_y
                            ];
                        }
                    }
                    // Cập nhật lại các vị trí sách
                    $shelfPositionModel->updateShelfPositions($id, $parsed_positions);

                    // Thông báo thành công
                    $_SESSION['success'] = 'Cập nhật kệ thành công!';
                    header('Location: /admin/shelves');
                    exit();
                } catch (\Exception $e) {
                    $errors['general'] = 'Có lỗi xảy ra khi lưu dữ liệu: ' . $e->getMessage();
                }
            }
            // Render lại dữ liệu cũ nếu có lỗi
            $shelf['name'] = $name;
            $shelf['location_note'] = $location_note;
            // Chuẩn bị lại positions cho view (chỉ có title, color, không có x, y)
            $positions = [];
            foreach ($positions_input as $index => $pos) {
                $parts = explode('||', $pos);
                $positions[] = [
                    'title' => trim($parts[0] ?? ''),
                    'color' => $parts[1] ?? '#FFD700',
                    'position_x' => $index % $cols,
                    'position_y' => floor($index / $cols)
                ];
            }
        }
        // Truyền dữ liệu ra view
        $data = [
            'shelf' => $shelf,
            'positions' => $positions,
            'cols' => $cols,
            'rows' => $rows,
            'errors' => $errors
        ];
        return $this->renderViewAdmin('shelves.update', $data);
    }

    public function getPositionsJson($shelf_id) {
        $positions = (new \Lecon\Mvcoop\Models\ShelfPosition())->getByShelfId($shelf_id);
        header('Content-Type: application/json');
        echo json_encode($positions);
        exit();
    }
}