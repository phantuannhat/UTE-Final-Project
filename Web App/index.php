<?php
session_start();
require './connect/config.php';
error_reporting(0);
$title = 'QUẢN LÝ';
require './pages/head.php';
$session = $_SESSION['username'];
?>

<body class="boxed-layout">
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <strong class="block m-t-xs"> QUẢN TRỊ VIÊN</strong></span>
                            </a>
                        </div>
                    </li>
                    <!-- vertical menu -->
                    <li <?= ($_GET['page'] == null) ? 'class="active"' : null; ?>>
                        <a href="/"><i class="fa fa-home"></i> <span class="nav-label">Trang chủ</span></a>
                    </li>
                    <?php if (isset($session)) : ?>
                        <li <?= ($_GET['page'] == 'admin') ? 'class="active"' : null; ?>>
                            <a href="admin.html"><i class="fa fa-user-circle" aria-hidden="true"></i> <span class="nav-label">Quản lý</span></a>
                        </li>
                        <li <?= ($_GET['page'] == 'search') ? 'class="active"' : null; ?>>
                            <a href="search.html"><i class="fa fa-search" aria-hidden="true"></i><span class="nav-label">Lọc tìm kiếm</span></a>
                        </li>
                        <li <?= ($_GET['page'] == 'add') ? 'class="active"' : null; ?>>
                            <a href="add.html"><i class="fa fa-plus-square" aria-hidden="true"></i><span class="nav-label">Thêm thùng rác</span></a>
                        </li>
                        <li <?= ($_GET['page'] == 'change') ? 'class="active"' : null; ?>>
                            <a href="change.html"><i class="fa fa-address-card" aria-hidden="true"></i><span class="nav-label">Thông tin nhân viên</span></a>
                        </li>
                        <li <?= ($_GET['page'] == 'history') ? 'class="active"' : null; ?>>
                            <a href="history.html"><i class="fa fa-history" aria-hidden="true"></i><span class="nav-label">Lịch sử</span></a>
                        </li>
                        <li>
                            <a href="./logout.php?destroy=username"><i class="fa fa-power-off" aria-hidden="true"></i> <span class="nav-label">Đăng xuất</span></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <?php if (isset($session)) : ?>
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                            <form role="search" class="navbar-form-custom" action="search_results.html">
                                <div class="form-group">
                                    <input type="text" placeholder="Tìm kiếm..." class="form-control" name="top-search" id="top-search">
                                </div>
                            </form>
                        </div>
                        <ul class="nav navbar-top-links navbar-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                    <i class="fa fa-user"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-messages">
                                    <li>
                                        <div class="dropdown-messages-box">
                                            <a class="dropdown-item float-left" href="profile.html">
                                                <div class="profile-image" style="width: auto;">
                                                    <img src="https://images-platform.99static.com//U0BKLXZyKBujIj4yPOuZ5s64hiQ=/652x722:1392x1462/fit-in/500x500/99designs-contests-attachments/123/123084/attachment_123084195" alt="Bản Quyền cấm copy" class="rounded-circle circle-border">
                                                </div>
                                            </a>
                                            <div class="media-body">
                                                <div class="text-center">
                                                    <a href="/" class="no-margins" style="text-transform: uppercase;">
                                                        <h4><?php $row = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `admin` WHERE `username`='$session'"));
                                                            echo ($row['name']); ?></h4>
                                                    </a>
                                                </div>
                                                <h4>Cấp độ : <b class="text-danger">Nhân viên</b></h4>
                                                <small>
                                                    Mọi nhu thắc mắc vui lòng liên hệ với chúng tôi ! <a href="https://ute.udn.vn/">Sinh viên ĐH SPKT ĐN</a>
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                    <i class="fa fa-bell"></i> <span class="label label-primary">3</span>
                                </a>
                                <ul class="dropdown-menu dropdown-alerts">
                                    <li>
                                        <a href="#" class="dropdown-item">
                                            <div>
                                                <i class="fa fa-code fa-fw"></i> Cập nhật lần 2
                                                <span class="float-right text-muted small">4 minutes ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li>
                                        <a href="#" class="dropdown-item">
                                            <div>
                                                <i class="fa fa-code fa-fw"></i> Cập nhật lần 1
                                                <span class="float-right text-muted small">12 minutes ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li>
                                        <a href="#" class="dropdown-item">
                                            <div>
                                                <i class="fa fa-upload fa-fw"></i> Kết nối server
                                                <span class="float-right text-muted small">2 minutes ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li>
                                        <div class="text-center link-block">
                                            <a href="#" class="dropdown-item">
                                                <strong>See All Alerts</strong>
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="logout.php?destroy=username">
                                    <i class="fa fa-sign-out"></i> Log out
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- current page -->
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?php if (!isset($session)) {
                            echo 'Đăng nhập';
                        } else {
                            switch ($_GET['page']) {
                                case null:
                                    echo 'Trang chủ';
                                    break;
                                case 'admin':
                                    echo 'Trang quản lý';
                                    break;
                                case 'search':
                                    echo 'Lọc tìm kiếm';
                                    break;
                                case 'add':
                                    echo 'Thêm thùng rác mới';
                                    break;
                                case 'change':
                                    echo 'Thay đổi thông tin';
                                    break;
                                case 'history':
                                    echo 'Lịch sử đổ rác';
                                    break;
                            }
                        } ?></h2>
                    <ol class="breadcrumb">
                        <?php if (isset($session)) : ?>
                            <li>
                                <a href="/">Quản trị viên</a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="/">Trang chủ</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <strong><?php switch ($_GET['page']) {
                                        case null:
                                            echo 'Trang chủ';
                                            break;
                                        case 'admin':
                                            echo 'Quản lý';
                                            break;
                                        case 'search':
                                            echo 'Tìm kiếm';
                                            break;
                                        case 'add':
                                            echo 'Thêm';
                                            break;
                                        case 'change':
                                            echo 'Thay đổi thông tin';
                                            break;
                                        case 'history':
                                            echo 'Lịch sử đổ rác';
                                            break;
                                    }
                                    ?></strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">
                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="social-feed-separated">
                            <div class="social-feed-box">
                                <div class="social-avatar">
                                    <h3 style="color: #2e80b3" class="text-center"><?php echo !isset($session) ? 'Điều trước tiên hãy đăng nhập' : 'Chào mừng bạn đến với hệ thống quản lý !'; ?></h3>

                                    <?php if (isset($session)) :
                                        switch ($_GET['page']):
                                                /* THAY ĐỔI THÔNG TIN NHÂN VIÊN */
                                            case 'change':
                                                $row = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `admin` WHERE `username`='$session'"));
                                    ?>
                                                <hr>
                                                <form id="change" method="POST" action="" class="form-horizontal">
                                                    <div class="form-group"><label class="col-sm-2 control-label">UID</label>
                                                        <div class="col-sm-10"><input type="text" name="uid" value="<?= $row['uid']; ?>" class="form-control" readonly></div>
                                                    </div>
                                                    <div class="form-group"><label class="col-sm-2 control-label">Họ và tên</label>
                                                        <div class="col-sm-10"><input type="text" name="name" value="<?= $row['name']; ?>" class="form-control"></div>
                                                    </div>
                                                    <div class="form-group"><label class="col-sm-2 control-label">Ngày sinh</label>
                                                        <div class="col-sm-10"><input type="date" name="birthday" value="<?= $row['birthday']; ?>" class="form-control"></div>
                                                    </div>
                                                    <div class="form-group"><label class="col-sm-2 control-label">Số điện thoại</label>
                                                        <div class="col-sm-10"><input type="number" name="phone" value="<?= $row['phone']; ?>" class="form-control"></div>
                                                    </div>
                                                    <div class="form-group"><label class="col-sm-2 control-label">Email</label>
                                                        <div class="col-sm-10"><input type="email" name="email" value="<?= $row['email']; ?>" class="form-control"></div>
                                                    </div>
                                                    <div class="form-group"><label class="col-sm-2 control-label">Địa chỉ</label>
                                                        <div class="col-sm-10"><input type="text" name="address" value="<?= $row['address']; ?>" class="form-control"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <button style="float: right" id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Thay đổi</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php break;
                                                /* TRANG QUẢN LÝ */
                                            case 'admin':
                                                require './manage.php';
                                                break;
                                                /* TRANG LỌC TÌM KIẾM */
                                            case 'search':
                                                require './search.php';
                                                break;
                                                /* LỊCH SỬ ĐỔ RÁC */
                                            case 'history':
                                                require './history.php';
                                                break;
                                                /* THÊM THÙNG RÁC MỚI */
                                            case 'add': ?>
                                                <hr>
                                                <form id="Addtrash" method="POST" action="" class="form-horizontal">
                                                    <div class="form-group"><label class="col-sm-2 control-label">THÊM MÃ TOKEN</label>
                                                        <div class="col-sm-10"><input type="text" name="token" value="" class="form-control" autocomplete="off" placeholder="Vui lòng nhập chính xác"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <button style="float: right" id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Thêm</button>
                                                        </div>
                                                    </div>
                                                </form>
                                    <?php break;
                                                /* TRANG CHỦ */
                                            case null:
                                                require './maps.php';
                                                break;
                                        endswitch;
                                    endif;
                                    ?>
                                </div>
                                <!-- TRANG ĐĂNG NHẬP -->
                                <div class="social-body">
                                    <?php if (!isset($session)) : ?>
                                        <form id="Login" method="POST" action="" class="form-horizontal">
                                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                                <div class="col-sm-10"><input type="text" name="username" value="" class="form-control" autocomplete="off"></div>
                                            </div>
                                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                                <div class="col-sm-10"><input type="password" name="password" value="" class="form-control"></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-4 col-sm-offset-2">
                                                    <button id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Đăng nhập</button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Phân cắt trang -->

            </div>
            <div class="footer">
                <div>
                    &copy; 2022 HỆ THỐNG QUẢN LÝ RÁC THẢI - COVID19
                </div>
            </div>
        </div>
    </div>
    <?php require './pages/footer.php'; ?>