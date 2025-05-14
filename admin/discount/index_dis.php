<?php
$title = 'Quản Lý Mã Giảm Giá';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
     header("Location: ../index.php");
     die();
 }
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');

?>
<div class="d-flex justify-content-between align-items-center mb-3">
     <div class="page-title text-info">Mã giảm giá</div>
     <div><button class="btn-add btn btn-success" data-toggle="modal" data-target="#modal-add" id="btn-add">Thêm</button></div>


</div>
<div id="load_data"></div>
<!-- Modal edit -->
<div class='modal fade' id='modal-edit' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden="true">
     <div class='modal-dialog modal-dialog-centered'>
          <div class='modal-content'>
          <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Sửa mã giảm giá</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class='modal-body'>
                    <div id="form-edit-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="edit-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Mã giảm giá</div>
                                   <input type='text' id='code' disabled='disabled' name='id_edit' value="" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tên mã giảm giá</div>
                                   <input type='text' id='namecode' name='name_code' value="" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Khuyến mãi (%)</div>
                                   <input type='number' id='percentcode' name='percent_code' value="" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Ngày bắt đầu</div>
                                   <input type='date' id='firstday' name='first_day' value="" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Ngày kết thúc</div>
                                   <input type='date' id='finishday' name='finish_day' value="" class="form-control w-50">
                              </div>
                         </form>
                    </div>
               </div>
               <div class='modal-footer'>
                    <button type='button' class='btn-add btn btn-success' data-bs-dismiss='modal' data-dismiss="modal" name='edit_coupons' id='btn-edit-submit'>Submit</button>
               </div>

          </div>
     </div>
</div>
<!-- Modal add -->
<div class=" modal fade" id="modal-add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Thêm mã giảm giá</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="form-add-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tên mã giảm giá</div>
                                   <input type="text" id="nameadd" name="name" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Khuyến mãi (%)</div>
                                   <input type="number" id="percentcodeadd" name="percent_code" class="form-control w-50" min='0' max=100>
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Ngày bắt đầu</div>
                                   <input type="date" id="startdayadd" name="begin_day" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Ngày kết thúc</div>
                                   <input type="date" id="enddayadd" name="end_day" class="form-control w-50">
                              </div>
                         </form>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn-add btn btn-success" data-bs-dismiss="modal" data-dismiss="modal" id="btn-submit" name="add_coupons">Submit</button>
               </div>
          </div>
     </div>
</div>

<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>