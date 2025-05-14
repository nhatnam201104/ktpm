<?php
$title = 'Quản Lý Nhà Cung Cấp';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
     echo 'Cannot access';
     die();
 }
 
?>

<div class="d-flex justify-content-between align-items-center mb-3">
     <div class="page-title text-info">Nhà cung cấp</div>
     <div><button class="btn-add btn btn-success" data-toggle="modal" data-target="#modal-add-supplier" id="btn-add-supplier">Thêm</button></div>
</div>

<div id="load-supplier-data">
</div>
<!-- Modal edit -->
<div class="modal fade" id="modal-edit-supplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Sửa nhà cung cấp</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-add-supplier-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="form-add-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Mã nhà cung cấp</div>
                                   <input type="number" id="id-edit-supplier" disabled=disabled class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tên nhà cung cấp</div>
                                   <input type="text" id="name-edit-supplier" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Địa chỉ</div>
                                   <input type="text" id="address-edit-supplier" class="form-control w-50">
                              </div>
                         </form>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" name="edit_supplier" id="btn-editsupplier-submit" class="btn btn-primary" data-dismiss="modal">Submit</button>
               </div>
          </div>
     </div>
</div>
<!-- Modal add -->
<div class="modal fade" id="modal-add-supplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Thêm nhà cung cấp</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-add-supplier-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="form-add-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Tên nhà cung cấp</div>
                                   <input type="text" id="name-supplier" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Địa chỉ</div>
                                   <input type="text" id="address-supplier" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Danh mục sản phẩm</div>
                                   <select name="" id="select" class="form-control w-50">

                                   </select>
                              </div>
                         </form>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" name="add_supplier" id="btn-addsupplier-submit">Submit</button>
               </div>
          </div>
     </div>
</div>
<!-- Modal option -->
<div class="modal fade" id="modal-option" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Thêm danh mục sản phẩm</div>
                    <button type="button" class="btn-close btn position-absolute" data-bs-dismiss="modal" aria-label="Close" data-dismiss="modal">
                         <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

               </div>
               <div class="modal-body">
                    <div id="form-add-supplier-container" class="w-100 d-flex flex-column justify-content-center p-3">
                         <form method="POST" id="form-add-data" name="">
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">ID Nhà cung cấp</div>
                                   <input type="number" disabled=disabled id="NCC" class="form-control w-50">
                              </div>
                              <div class="d-flex form-group align-items-center justify-content-between">
                                   <div class="font-weight-bold">Danh mục sản phẩm</div>
                                   <select name="" id="select-catesup" class="form-control w-50">

                                   </select>
                              </div>
                         </form>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" name="add_catesupplier" id="btn-add-catesup-submit">Submit</button>
               </div>
          </div>
     </div>
</div>
<!-- Modal view -->
<div class="modal fade" id="modal-view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Danh sách danh mục sản phẩm</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">X</button>
               </div>
               <div class="modal-body">
                    <div id="view-supplier-category">
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" name="add_catesupplier" id="btn-add-catesup-submit">ok</button>
               </div>
          </div>
     </div>
</div>

<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>