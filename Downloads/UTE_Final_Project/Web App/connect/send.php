<?php
session_start();
error_reporting(0);
require './config.php';
if (isset($_GET['key'])) :
        switch ($_GET['key']) {
                case 'login':
                        if (isset($_POST['username']) && isset($_POST['username']) && $_POST['username'] != '' && $_POST['password'] != '') {
                                $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
                                $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
                                $acc = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `admin` WHERE `username`='" . $username . "'"));
                                if (!isset($acc)) {
                                        echo (false);
                                } else if ($password != $acc['password']) {
                                        echo (false);
                                } else {
                                        $_SESSION['username'] = $username;
                                        echo (true);
                                }
                        } else {
                                echo ('null');
                        }
                        mysqli_close($connect);
                        break;
                case 'change':
                        if (isset($_SESSION['username']) && isset($_POST['uid']) && isset($_POST['name']) && isset($_POST['birthday']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['address'])) {
                                $uid = htmlspecialchars($_POST['uid'], ENT_QUOTES);
                                $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
                                $birthday = htmlspecialchars($_POST['birthday'], ENT_QUOTES);
                                $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
                                $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
                                $address = htmlspecialchars($_POST['address'], ENT_QUOTES);
                                $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES);

                                $check = mysqli_query($connect, "UPDATE `admin` SET `uid`='$uid',`name`='$name',`birthday`='$birthday',`phone`='$phone',`email`='$email',`address`='$address' WHERE username = '$username'");
                                if ($check) {
                                        echo (true);
                                } else {
                                        echo (false);
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'adduser':
                        if (isset($_POST['trash_can_id']) && isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['ward']) && isset($_POST['district']) && isset($_POST['city'])) {

                                if ($_POST['trash_can_id'] != '' && $_POST['name'] != '' && $_POST['phone'] != '' && $_POST['email'] != '' && $_POST['address'] != '' && $_POST['ward'] != '' && $_POST['district'] != '' && $_POST['city'] != '') :
                                        $trash_can_id = htmlspecialchars($_POST['trash_can_id'], ENT_QUOTES);
                                        $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
                                        $dayofbirth = htmlspecialchars($_POST['dayofbirth'], ENT_QUOTES);
                                        $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
                                        $isolatedday = htmlspecialchars($_POST['isolatedday'], ENT_QUOTES);
                                        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
                                        $address = htmlspecialchars($_POST['address'], ENT_QUOTES);
                                        $ward = htmlspecialchars($_POST['ward'], ENT_QUOTES);
                                        $district = htmlspecialchars($_POST['district'], ENT_QUOTES);
                                        $city = htmlspecialchars($_POST['city'], ENT_QUOTES);

                                        $check_insert = mysqli_query($connect, "INSERT INTO `user`(`trash_can_id`, `name`, `dayofbirth`, `phone`, `isolatedday`, `email`, `address`, `ward`, `district`, `city`) VALUES ('$trash_can_id','$name','$dayofbirth','$phone','$isolatedday','$email','$address','$ward','$district','$city')");
                                        if ($check_insert) {
                                                echo (true);
                                        } else {
                                                echo (false);
                                        }
                                else :
                                        echo ('null');
                                endif;
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'edit':
                        if (isset($_POST['trash_can_id'])) {
                                $trash_can_id = htmlspecialchars($_POST['trash_can_id'], ENT_QUOTES);
                                $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
                                $dayofbirth = htmlspecialchars($_POST['dayofbirth'], ENT_QUOTES);
                                $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
                                $isolatedday = htmlspecialchars($_POST['isolatedday'], ENT_QUOTES);
                                $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
                                $address = htmlspecialchars($_POST['address'], ENT_QUOTES);
                                $ward = htmlspecialchars($_POST['ward'], ENT_QUOTES);
                                $district = htmlspecialchars($_POST['district'], ENT_QUOTES);
                                $city = htmlspecialchars($_POST['city'], ENT_QUOTES);

                                if ($_POST['trash_can_id'] != '' && $_POST['name'] != '' && $_POST['phone'] != '' && $_POST['email'] != '' && $_POST['address'] != '' && $_POST['ward'] != '' && $_POST['district'] != '' && $_POST['city'] != '') {
                                        $check_update = mysqli_query($connect, "UPDATE `user` SET `name`='$name',`dayofbirth`='$dayofbirth',`phone`='$phone',`isolatedday`='$isolatedday',`email`='$email',`address`='$address',`ward`='$ward',`district`='$district',`city`='$city' WHERE trash_can_id = '$trash_can_id'");
                                        if ($check_update) {
                                                echo (true);
                                        } else {
                                                echo (false);
                                        }
                                } else {
                                        echo ('null');
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'addtrash':
                        if (isset($_POST['token'])) {
                                $token = htmlspecialchars($_POST['token'], ENT_QUOTES);
                                if ($token !== '') {
                                        $check_insert = mysqli_query($connect, "INSERT INTO `trash_can`(`token`) VALUES ('$token')");
                                        if ($check_insert) {
                                                echo (true);
                                        }
                                } else {
                                        echo ('null');
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'xoatrash':
                        if (isset($_POST['id'])) {
                                $check_delete = mysqli_query($connect, "DELETE FROM `trash_can` WHERE id= '{$_POST['id']}'");
                                if ($check_delete) {
                                        echo (true);
                                } else {
                                        echo ($_POST['id']);
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'xoauser':
                        if (isset($_POST['id'])) {
                                $check_delete = mysqli_query($connect, "DELETE FROM `user` WHERE trash_can_id = '{$_POST['id']}'");
                                if ($check_delete) {
                                        echo (true);
                                } else {
                                        var_dump($check_delete);
                                        echo ($_POST['id']);
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'search':
                        if (isset($_POST['keyword'])) {
                                $keyword = $_POST['keyword'];
                                $result_search = mysqli_query($connect, "SELECT * FROM user WHERE city like '%$keyword%' OR district like '%$keyword%' OR ward like '%$keyword%'");
                                if (mysqli_num_rows($result_search) > 0) {
                                        $rows = array();
                                        while ($r = mysqli_fetch_assoc($result_search)) {
                                                $rows[] = $r;
                                        }
                                        echo json_encode($rows);
                                } else {
                                        echo '{"result": "no data found"}';
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'xem':
                        if (isset($_POST['id'])) {
                                $xem = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `user` WHERE `trash_can_id`='{$_POST['id']}'"));
                                if (isset($xem)) {
                                        echo (json_encode($xem));
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'getInfo':
                        $getInTrash = mysqli_query($connect, "SELECT `id`, `weight`,`garbagepercent` FROM `trash_can`");
                        //$getInUser = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `name` FROM `user`"));
                        if (mysqli_num_rows($getInTrash) > 0) {
                                $rows = array();
                                while ($r = mysqli_fetch_assoc($getInTrash)) {
                                        $rows[] = $r;
                                }
                                echo json_encode($rows);
                        } else {
                                echo '{"result": "no data found"}';
                        }

                        mysqli_close($connect);
                        break;
                case 'trash_can':
                        if (isset($_POST['id'])) {
                                $xem = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `trash_can` WHERE `id`='{$_POST['id']}'"));
                                if (isset($xem)) {
                                        echo (json_encode($xem));
                                }
                                mysqli_close($connect);
                        } else {
                                require('../pages/404.php');
                        }
                        break;
                case 'getMaps':
                        $result = mysqli_query($connect, "SELECT `location` , `garbagepercent` FROM `trash_can`");
                        if (mysqli_num_rows($result) > 0) {
                                $rows = array();
                                while ($r = mysqli_fetch_assoc($result)) {
                                        $rows[] = $r;
                                }
                                echo json_encode($rows);
                        } else {
                                echo '{"result": "no data found"}';
                        }
                        mysqli_close($connect);
                        break;
                default:
                        require('../pages/404.php');
        }
else :
        die(HACKER);
endif;
