<?php
$title = 'Thống kê';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 2) {
    echo 'Cannot access';
    die();
}
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');
?>
<div>
    <div>
        <h3>Thống kê</h3>
    </div>
    <div class="d-flex mb-5">
        <div class="col-md-3">
            <label for="">Ngày bắt đầu</label>
            <input type="date" class="form-control w-50" id="s-day">
        </div>
        <div class="col-md-3">
            <label for="">Ngày kết thúc</label>
            <input type="date" class="form-control w-50" id="e-day">
        </div>
        <div class="col-md-3">
            <label for="">Danh mục sản phẩm</label>
            <select name="" id="select-category" class="form-control w-50">
                <option value="-1">Chọn danh mục</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary mt-5" id="statistics">Thống kê</button>
        </div>
    </div>
    <div class="d-flex mb-5">
        <label for="">Tổng doanh thu:</label>
        <input type="text" id="totalSum" disabled=disabled>
    </div>
    <div class="d-flex mb-5">
        <canvas id="myChart" style="width:100%; height:300px"></canvas>
    </div>
    <div>
        <div class='font-weight-bold'>Danh sách sản phẩm đã bán</div>
        <div>
            <table width=100% class='table table-striped table-hover table-discount' id="listProSold">
            </table>
    </div>
</div>