<div class="modal inmodal" id="add" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content animated flipInY">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>THÊM THÔNG TIN NGƯỜI DÙNG</h5>
        </div>
        <div class="ibox-content">
          <form id="Add" method="POST" action="" class="form-horizontal">
            <div class="form-group"><label class="col-sm-2 control-label">ID</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="trash_can_id" id="iput" readonly>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Tên người dùng</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" placeholder="Tên người dùng">
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Ngày sinh</label>
              <div class="col-sm-10">
                <input type="date" class="form-control" name="dayofbirth" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Số điện thoại</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" name="phone" placeholder="Số điện thoại">
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Ngày bị nhiễm</label>
              <div class="col-sm-10">
                <input type="date" class="form-control" name="isolatedday" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" name="email" placeholder="Email">
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Tỉnh, TP</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="city" placeholder="Tỉnh, TP">
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Quận, huyện</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="district" placeholder="Quận/huyện...">
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Xã, phường</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="ward" placeholder="Xã/phường...">
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Địa chỉ</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="address" placeholder="Địa chỉ (số nhà, đường)">
              </div>
            </div>
            <div class="form-group text-center">
              <button class="btn btn-primary" id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Thực hiện</button>
              <button type="button" class="btn btn-white" id="close" data-dismiss="modal">Đóng</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content animated flipInY">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>CHỈNH SỬA THÔNG TIN NGƯỜI DÙNG</h5>
        </div>
        <div class="ibox-content">
          <form id="Edit" method="POST" action="" class="form-horizontal">
            <div class="form-group"><label class="col-sm-2 control-label">#UID</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" id="val0" name="trash_can_id" readonly>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Tên người dùng</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="val1" name="name" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Ngày sinh</label>
              <div class="col-sm-10">
                <input type="date" class="form-control" id="val2" name="dayofbirth" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Số điện thoại</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" id="val3" name="phone" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Ngày bị nhiễm</label>
              <div class="col-sm-10">
                <input type="date" class="form-control" id="val4" name="isolatedday" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="val5" name="email" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Địa chỉ</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="val6" name="address" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Xã, phường</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="val7" name="ward" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Quận, huyện</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="val8" name="district" required>
              </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Tỉnh, TP</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="val9" name="city" required>
              </div>
            </div>
            <div class="form-group text-center">
              <button class="btn btn-primary" id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Thực hiện</button>
              <button type="button" class="btn btn-white" id="close" data-dismiss="modal">Đóng</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>