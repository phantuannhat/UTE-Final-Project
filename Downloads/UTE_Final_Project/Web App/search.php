<div class="ibox">
    <div class="ibox-title">
        <h5 class="text-muted text-center">Tìm kiếm theo danh sách hiện có</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
            <a class="close-link">
                <i class="fa fa-times"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="col-form-label" for="status">Tỉnh / Thành phố</label>
                    <select class="form-control" id="city">
                        <option value="" selected>Chọn tỉnh thành</option>
                        <?php
                        $json = file_get_contents('assets/vietnam.json', false);
                        $data = json_decode($json, TRUE);
                        for ($i = 0; $i < count($data); $i++) {
                        ?>
                            <option value="<?= $i; ?>"><?= $data[$i]['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="col-form-label" for="status">Quận / Huyện / Tp</label>
                    <select class="form-control" id="district">
                        <option value="" selected>Chọn quận huyện</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="col-form-label" for="status">Xã / Phường</label>
                    <select class="form-control" id="ward">
                        <option value="" selected>Chọn phường xã</option>
                    </select>
                </div>
            </div>
            <p>
            <div class="col-sm-2 text-center">
                <br>
                <button class="btn btn-primary btn-rounded" id="button_search">Tìm kiếm</button>
            </div>
            </p>
        </div>
    </div>
    <div class="row">
        <div id="result_search"></div>
    </div>
</div>