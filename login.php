<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/login_style.css">
    <title>Đăng ký | Đăng nhập</title>

</head>

<body>
<?php
    session_start();
    if (isset($_SESSION['error'])) {
      echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
      unset($_SESSION['error']);
    }
  ?>


    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="register.php" method="POST">
                <h1>Tạo tài khoản mới</h1>
                <div class="social-icons">
                    <a href="#" class="icon" id="icon_gg"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon" id="icon_fb"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon" id="icon_git"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon" id="icon_lk"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span></span>
                <input type="text" name="username" placeholder="Tên tài khoản" required>
                <input type="text" name="fullname" placeholder="Họ tên người dùng" required>
                <input type="number" name="cccd" placeholder="CCCD" pattern="\d{12}" title="CCCD phải có đúng 12 chữ số" required>
                <input type="password" name="password" placeholder="Mật khẩu" minlength="8" maxlength="30" required>
                <button type="submit">Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="checkLogin.php" method="POST">
                <h1>Đăng nhập</h1>
                <div class="social-icons">
                    <a href="#" class="icon" id="icon_gg"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon" id="icon_fb"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon" id="icon_git"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon" id="icon_lk"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span></span>
                <input type="text" name="username" placeholder="Tên tài khoản" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <a href="quenmatkhau.php">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Mừng bạn trở lại!</h1>
                    <p>Sử dụng tài khoản của bạn để đăng nhập</p>
                    <button class="hidden" id="login">Đăng nhập</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Chào bạn!</h1>
                    <p>Hãy đăng ký tài khoản để khám phá cửa hàng của chúng tôi</p>
                    <button class="hidden" id="register">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>