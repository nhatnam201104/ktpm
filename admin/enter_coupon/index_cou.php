<?php
$title = 'Quản Lý Phiếu nhâp';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
     echo 'Cannot access';
     die();
 }
?>

<div class="d-flex justify-content-between align-items-center mb-3">
     <div class="page-title text-info">Phiếu nhập</div>
     <div><button class="btn-add btn btn-success" data-toggle="modal" data-target="#modal-add-entercoupon" id="btn-add-entercoupon">Thêm</button></div>
 
     
</div>

<div id="load-entercoupon-data">
</div>
<!-- Modal edit -->
<div class="modal fade" id="modal-edit-entercoupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Sửa phiếu nhập</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-edit-entercoupon-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="form-edit-entercoupon-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">ID Phiếu nhập</div>
                                   <input type="text" id="id_sua" disabled=disabled class="form-control w-50" value="">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Nhân viên</div>
                                   <select name="" id="name-edit-entercoupon-staff" class="form-control w-50"></select>
                              </div>
                         </form>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" name="edit_supplier" id="btn-editentercoupon-submit" class="btn btn-primary" data-dismiss="modal">Submit</button>
               </div>
          </div>
     </div>
</div>
<!-- Modal add -->
<div class="modal fade" id="modal-add-entercoupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Thêm phiếu nhập</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-add-entercoupon-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="form-add-entercoupon-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tên nhà cung cấp</div>
                                   <select id="select-supplier" name="supplier_id" class="form-control w-50">

                                   </select>
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Bạn đã kiểm tra nhà cung cấp?</div>
                                   <input type="button" id="check-to-add-product" value="Ok" class="btn-success">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tên sản phẩm</div>
                                   <select id="select-name-product" name="select-name-product" class="form-control w-50"></select>
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Số lượng</div>
                                   <input type="number" name="product-qty" id="product-qty" class="form-control w-50" min="1" max="999999">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Giá nhập</div>
                                   <input type="number" name="enter-price" id="enter-price" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tỉ suất lợi nhuận</div>
                                   <input type="number" name="product-profit" id="product-profit" class="form-control w-50" min="1" max="50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <input type="button" name="product-qty-add" id="product-update" value="Thêm" class="form-control w-30 btn-success">
                              </div>
                         </form>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" name="add_entercoupon" id="btn-add-entercoupon-submit">Xong</button>
               </div>
          </div>
     </div>
</div>
<!-- Modal option -->
<div class="modal fade" id="modal-view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Chi tiết phiếu nhập</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-view-entrydetails-container">

                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" name="add_catesupplier" id="btn-add-catesup-submit">Xong</button>
               </div>
          </div>
     </div>
</div>

<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>