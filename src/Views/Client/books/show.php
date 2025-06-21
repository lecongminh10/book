<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Book Covers -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <img src="/uploads/<?php echo $book['cover_front']; ?>" class="img-fluid mb-3" alt="Front Cover">
                    <img src="/uploads/<?php echo $book['cover_back']; ?>" class="img-fluid" alt="Back Cover">
                </div>
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><?php echo $book['title']; ?></h1>
                    <h5 class="card-subtitle mb-3 text-muted"><?php echo $book['author']; ?></h5>

                    <div class="mb-3">
                        <strong>Thể loại:</strong> <?php echo $book['category_name']; ?>
                    </div>

                    <div class="mb-3">
                        <strong>Năm xuất bản:</strong> <?php echo $book['publish_year']; ?>
                    </div>

                    <div class="mb-3">
                        <strong>ISBN:</strong> <?php echo $book['isbn']; ?>
                    </div>

                    <div class="mb-3">
                        <strong>Vị trí:</strong> <?php echo $book['location_description']; ?>
                    </div>

                    <div class="mb-3">
                        <strong>Tóm tắt:</strong>
                        <p><?php echo $book['summary']; ?></p>
                    </div>

                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Borrowing Form -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Đặt mượn sách</h5>
                            </div>
                            <div class="card-body">
                                <form action="/borrow" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="borrow_date" class="form-label">Ngày mượn</label>
                                        <input type="date" class="form-control" id="borrow_date" name="borrow_date" 
                                               min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="return_date" class="form-label">Ngày trả</label>
                                        <input type="date" class="form-control" id="return_date" name="return_date" 
                                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Đặt mượn</button>
                                </form>
                            </div>
                        </div>

                        <!-- Read Online Button -->
                        <div class="mt-3">
                            <a href="/read/<?php echo $book['id']; ?>" class="btn btn-success">
                                Đọc online
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Vui lòng <a href="/auth/login">đăng nhập</a> để mượn sách hoặc đọc online.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const borrowDateInput = document.getElementById('borrow_date');
    const returnDateInput = document.getElementById('return_date');

    borrowDateInput.addEventListener('change', function() {
        const minReturnDate = new Date(this.value);
        minReturnDate.setDate(minReturnDate.getDate() + 1);
        returnDateInput.min = minReturnDate.toISOString().split('T')[0];
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 