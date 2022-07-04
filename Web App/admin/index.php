<?php
session_start();
require '../connect/config.php';
$title = 'ADMIN';
if (!isset($_SESSION['super_admin'])) :
    require('./login.php');
else :
    if (isset($_POST['submit'])) {
        $check = mysqli_query($connect, "INSERT INTO `admin`(`id`, `uid`,`username`, `password`, `name`, `birthday`, `phone`, `email`, `address`) VALUES (NULL,'{$_POST['uid']}','{$_POST['username']}','{$_POST['password']}','{$_POST['name']}','{$_POST['birthday']}','{$_POST['phone']}','{$_POST['email']}','{$_POST['address']}')");
        if ($check) {
            echo '<script>alert("Đăng kí thành công , đang chuyển hướng ...");setTimeout(function(){ window.location.href = "./home?page"});</script>';
        } else {
            echo '<script>alert("Chưa tạo thành công vui lòng thử lại !");</script>';
        }
    }
?>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title; ?></title>
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/animate.css" rel="stylesheet">
        <link href="../assets/css/style1.css" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css" rel="stylesheet" type="text/css">
        <style>
            th {
                font-size: 15px;
            }

            td {
                font-size: 14px;
            }
        </style>
    </head>

    <body class="top-navigation">
        <div id="wrapper">
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom white-bg">
                    <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
                        <strong class="navbar-brand">ADMIN +</strong>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-reorder"></i>
                        </button>

                        <div class="navbar-collapse collapse" id="navbar">
                            <ul class="nav navbar-nav mr-auto">
                                <li <?= ($_GET['page'] == null) ? 'class="active"' : null; ?>>
                                    <a aria-expanded="false" role="button" href="./home?page">Tổng quang</a>
                                </li>
                                <li <?= ($_GET['page'] == 'add_agent') ? 'class="active"' : null; ?>>
                                    <a aria-expanded="false" role="button" href="./home?page=add_agent">Thêm nhân viên</a>
                                </li>
                                <li <?= ($_GET['page'] == 'setting') ? 'class="active"' : null; ?>>
                                    <a aria-expanded="false" role="button" href="./home?page=setting">Cài Đặt hệ thống</a>
                                </li>
                            </ul>
                            <ul class="nav navbar-top-links navbar-right">
                                <li>
                                    <a href="../logout.php?destroy=admin">
                                        <i class="fa fa-sign-out"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <div class="wrapper wrapper-content">
                    <div class="container">

                        <?php if (isset($_GET['page'])) :
                            switch ($_GET['page']) {
                                case null:
                        ?>
                                    <div class="row animated fadeInRight">
                                        <div class="col-lg-8">
                                            <div class="ibox ">
                                                <div class="ibox-content">
                                                    <h3>Tình trạng số lượng truy cập của website</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="ibox ">
                                                <div class="ibox-title">
                                                    <h3>Thông tin về server</h3>
                                                </div>
                                                <div class="ibox-content">
                                                    <div class="row">
                                                        <h5>Hiển thị thông tin</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row animated fadeInRight">
                                        <div class="col-lg-12">
                                            <div class="ibox ">
                                                <div class="ibox-title">
                                                    <h5>Thống kê số nhân viên hiện có</h5>
                                                    <div class="ibox-tools">
                                                        <a class="collapse-link">
                                                            <i class="fa fa-chevron-up"></i>
                                                        </a>
                                                        <a class="close-link">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="ibox-content">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>#ID</th>
                                                                    <th>Tên nhân viên</th>
                                                                    <th>Ngày tháng năm sinh</th>
                                                                    <th>Số điện thoại</th>
                                                                    <th>Email</th>
                                                                    <th>Địa chỉ</th>
                                                                    <th>Username</th>
                                                                    <th>Password</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $result = mysqli_query($connect, "select * from admin");
                                                                foreach ($result as $data) : ?>
                                                                    <tr>
                                                                        <td><?= $data['id']; ?></td>
                                                                        <td><?= $data['name']; ?></td>
                                                                        <td><?= $data['birthday']; ?></td>
                                                                        <td><?= $data['phone']; ?></td>
                                                                        <td><?= $data['email']; ?></td>
                                                                        <td><?= $data['address']; ?></td>
                                                                        <td><?= $data['username']; ?></td>
                                                                        <td><?= $data['password']; ?></td>
                                                                    </tr>
                                                                <?php endforeach ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php
                                    break;
                                    // TRANG CÀI ĐẶT HỆ THỐNG
                                case 'setting':
                                ?>
                                    <div class="row animated fadeInRight">
                                        <div class="col-lg-12">
                                            <div class="ibox ">
                                                <div class="ibox-content">
                                                    <!-- <h3>TRANG CÀI ĐẶT HỆ THỐNG</h3> -->
                                                    <form action="" method="POST" class="form-horizontal">
                                                        <div class="form-group"><label class="col-sm-2 control-label">Domain</label>
                                                            <div class="col-sm-10"><input type="text" name="" value="http://<?= $_SERVER['SERVER_NAME']; ?>/" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Tiêu đề</label>
                                                            <div class="col-sm-10"><input type="text" name="" value="<?= $_SERVER['HTTP_REFERER']; ?>" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Url icon</label>
                                                            <div class="col-sm-10"><input type="text" name="" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Layout</label>
                                                            <div class="col-sm-10"><input type="text" name="" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Giới thiệu</label>
                                                            <div class="col-sm-10"><input type="text" name="" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <button class="btn btn-primary" type="submit" name="submit">Lưu thay đổi</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    break;
                                    //TRANG THÊM NHÂN VIÊN
                                case 'add_agent':
                                ?>
                                    <div class="row animated fadeInRight">
                                        <div class="col-lg-12">
                                            <div class="ibox ">
                                                <div class="ibox-content">
                                                    <form action="" method="POST" class="form-horizontal">
                                                        <div class="form-group"><label class="col-sm-2 control-label">UID</label>
                                                            <div class="col-sm-10"><input type="text" name="uid" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Tên nhân viên</label>
                                                            <div class="col-sm-10"><input type="text" name="name" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Ngày tháng năm sinh</label>
                                                            <div class="col-sm-10"><input type="date" name="birthday" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Số điện thoại</label>
                                                            <div class="col-sm-10"><input type="number" name="phone" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Email</label>
                                                            <div class="col-sm-10"><input type="email" name="email" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Địa chỉ</label>
                                                            <div class="col-sm-10"><input type="text" name="address" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                                            <div class="col-sm-10"><input type="text" name="username" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                                            <div class="col-sm-10"><input type="password" name="password" class="form-control" required></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <button class="btn btn-primary" type="submit" name="submit">Gửi</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                    break;
                            }
                        else :
                            header("location: ../admin/home?page");
                        endif; ?>

                    </div>
                </div>

                <div class="footer">
                    <div class="float-right">
                        10GB of <strong>250GB</strong> Free
                    </div>
                    <div>
                        UTE &copy; 20222
                    </div>
                </div>

            </div>
        </div>
    <?php
    require('../pages/footer.php');
endif;
    ?>