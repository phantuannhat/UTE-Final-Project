<?php
if (isset($_POST['submit'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    $acc = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `super_admin` WHERE `username`='" . $username . "'"));
    if (!$acc) {
        echo '<script>alert("Kiểm tra lại tài khoản hoặc mật khẩu !") </script>';
    } else if ($password != $acc['password']) {
        echo '<script>alert("Kiểm tra lại tài khoản hoặc mật khẩu !") </script>';
    } else {
        $_SESSION['super_admin'] = $username;
        echo '<script>alert("Đăng nhập thành công");setTimeout(function(){ window.location.href = "./home?page"});</script>';
    }
}
require('../pages/head.php');
?>
<body class="gray-bg">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <h3>Welcome to ADMIN+</h3>
            <p>Vui lòng đăng nhập để tiếp tục</p>
            <form class="m-t" role="form" action="" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required="">
                </div>
                <button type="submit" name="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
            <p class="m-t"> <small>ADMIN CONTROL PANEL &copy; 2022</small> </p>
        </div>
    </div>
<?php 
require('../pages/footer.php');
?>