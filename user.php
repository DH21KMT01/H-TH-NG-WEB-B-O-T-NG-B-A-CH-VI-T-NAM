<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Trang Chủ - Bảo Tàng Bùa Chú</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <button onclick="history.back()" style="padding: 8px 12px; background: #3498db; color: white; border: none; border-radius: 4px; margin: 10px;">
    🔙 Quay lại
  </button>

  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="index.php">Bảo Tàng Bùa Chú</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="collection.html">Bộ Sưu Tập</a></li>
          <li class="nav-item"><a class="nav-link" href="library.html">Du Lịch</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">Giới Thiệu</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Liên Hệ</a></li>
          
          <?php if (isset($_SESSION['username'])): ?>
            <!-- Nếu đã đăng nhập -->
            <li class="nav-item"><a class="nav-link" href="dat_ve.php">Đặt Vé</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Hồ Sơ</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Đăng Xuất</a></li>
          <?php else: ?>
            <!-- Chưa đăng nhập -->
            <li class="nav-item"><a class="nav-link" href="login.php">Đăng Nhập</a></li>
          <?php endif; ?>
          
        </ul>
      </div>
    </div>
  </nav>
  
  <header class="text-center py-5 bg-light">
    <h1>Chào mừng đến với Bảo Tàng Bùa Chú VN</h1>
    <p>Khám phá lịch sử và bí ẩn của các loại bùa chú.</p>
    
    <?php if (isset($_SESSION['username'])): ?>
      <p>👋 Xin chào, <strong><?php echo $_SESSION['username']; ?></strong></p>
    <?php endif; ?>
    
  </header>

  <section class="container my-5">
    <h2>Bộ sưu tập nổi bật</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card p-3">
          <img src="images/bua1.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Bùa Hộ Mệnh</h5>
            <p class="card-text">Mang lại may mắn và bảo vệ chủ nhân.</p>
          </div>
        </div>
      </div>
      <!-- Thêm các bộ sưu tập khác nếu muốn -->
    </div>
  </section>

  <footer class="mt-5 text-center">
    <p>© 2025 Bảo Tàng Bùa Chú VN</p>
  </footer>
</body>
</html>
