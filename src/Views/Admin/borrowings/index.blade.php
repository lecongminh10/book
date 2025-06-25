@extends('layouts.master')

@section('title')
Danh sách sách
@endsection
@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quản lý sách</h5>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Tổng số lượt mượn</h5>
                                    <h2><?php echo $stats['total_borrowings']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Đang chờ duyệt</h5>
                                    <h2><?php echo $stats['pending_count']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Đã duyệt</h5>
                                    <h2><?php echo $stats['approved_count']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Đã trả</h5>
                                    <h2><?php echo $stats['returned_count']; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'], '/admin/borrowings') !== false && strpos($_SERVER['REQUEST_URI'], '/pending') === false && strpos($_SERVER['REQUEST_URI'], '/active') === false && strpos($_SERVER['REQUEST_URI'], '/overdue') === false && strpos($_SERVER['REQUEST_URI'], '/returned') === false && strpos($_SERVER['REQUEST_URI'], '/rejected') === false ? 'active' : '' }}" href="/admin/borrowings">Tất cả</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'], '/pending') !== false ? 'active' : '' }}" href="/admin/borrowings/pending">Chờ duyệt</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'], '/active') !== false ? 'active' : '' }}" href="/admin/borrowings/active">Đang mượn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'], '/overdue') !== false ? 'active' : '' }}" href="/admin/borrowings/overdue">Quá hạn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'], '/returned') !== false ? 'active' : '' }}" href="/admin/borrowings/returned">Đã trả</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'], '/rejected') !== false ? 'active' : '' }}" href="/admin/borrowings/rejected">Đã từ chối</a>
                        </li>
                    </ul>

                    <!-- Borrowings Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sinh viên</th>
                                    <th>Sách</th>
                                    <th>Ngày mượn</th>
                                    <th>Ngày trả</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($borrowings as $borrowing): ?>
                                    <tr>
                                        <td><?php echo $borrowing['id']; ?></td>
                                        <td>
                                            <?php echo $borrowing['full_name']; ?><br>
                                            <small class="text-muted"><?php echo $borrowing['student_id']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo $borrowing['title']; ?><br>
                                            <small class="text-muted"><?php echo $borrowing['author']; ?></small>
                                        </td>
                                        <td><?php echo $borrowing['borrow_date']; ?></td>
                                        <td><?php echo $borrowing['return_date']; ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'returned' => 'info',
                                                'rejected' => 'danger'
                                            ];
                                            $statusText = [
                                                'pending' => 'Chờ duyệt',
                                                'approved' => 'Đã duyệt',
                                                'returned' => 'Đã trả',
                                                'rejected' => 'Từ chối'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass[$borrowing['status']]; ?>">
                                                <?php echo $statusText[$borrowing['status']]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($borrowing['status'] === 'pending'): ?>
                                                <a href="/admin/borrowings/approve/<?php echo $borrowing['id']; ?>" 
                                                   class="btn btn-sm btn-success">Duyệt</a>
                                                <a href="/admin/borrowings/reject/<?php echo $borrowing['id']; ?>" 
                                                   class="btn btn-sm btn-danger">Từ chối</a>
                                            <?php elseif ($borrowing['status'] === 'approved'): ?>
                                                <a href="/admin/borrowings/return/<?php echo $borrowing['id']; ?>" 
                                                   class="btn btn-sm btn-info">Xác nhận trả</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo $baseUrl . '?page=' . $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection