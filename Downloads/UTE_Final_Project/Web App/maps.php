<div class="alert alert-success" style="color:#1abc9c" role="alert">
    <font color="black" class="text-center">
        <h2>CONTROL PANEL</h2>
    </font>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-content product-box">
                <div class="product-desc text-center">
                    <div class="product-name">Số thùng rác hiện tại</div>
                    <div class="m-t text-righ">
                        <span class="btn btn-primary btn-rounded"><?php $num_trash = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM `trash_can`"));
                                                                    echo ($num_trash[0]); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-content product-box">
                <div class="product-desc text-center">
                    <div class="product-name">Thùng rác chưa hoạt động</div>
                    <div class="m-t text-righ">
                        <span class="btn btn-danger btn-rounded"><?php $num_user = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM `user`"));
                                                                    echo ($num_trash[0] - $num_user[0]); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-content product-box">
                <div class="product-desc text-center">
                    <div class="product-name">Số nhân viên</div>
                    <div class="m-t text-righ">
                        <span class="btn btn-warning btn-rounded"><?php $num_admin = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM `admin`"));
                                                                    echo ($num_admin[0]); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row m-t-lg">
    <div class="col-xl-12">
        <div class="ibox-content">
            <!-- <iframe width="100%" height="500" style="border:0" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBH6z9pLP8iIZWzfXFBV_XUjrAY27Vo2XM
    &q=16.0790225,108.2105069&zoom=15">
        </iframe> -->
            <div id="map"></div>
        </div>
    </div>
</div>
