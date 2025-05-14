<link rel="stylesheet" type="text/css" href="../../assets/css/dashboard.css">
<?php

$title = 'Quản Lý Đơn Hàng';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 4 && $_SESSION["user"]["role_id"] != 2) {
    echo 'Cannot access';
    die();
}


$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Kiểm tra xem ngày bắt đầu và kết thúc có được cung cấp không
if ($start_date !== '' && $end_date !== '') {
    // Tạo câu truy vấn SQL để lấy các đơn hàng trong khoảng ngày đã chọn
    $sql = "SELECT * FROM `order` WHERE created_date BETWEEN '$start_date' AND '$end_date'";
} else {
    // Nếu không có ngày bắt đầu và kết thúc, lấy tất cả các đơn hàng
    $sql = "SELECT * FROM `order`";
}
$sql .= " ORDER BY order.id desc";
$data = executeResult($sql);

?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 table-responsive">
        <h1 class=" badge-pill badge-primary" style="display:flex;justify-content: center;padding: 10px;">Quản Lý Đơn Hàng</h1>

        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Từ ngày:</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="col-md-3">
                <label for="end_date">Đến ngày:</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <div class="col-md-2">
                <button onclick="applyFilter()" class="btn btn-primary" style="margin-top: 30px;">Lọc</button>
            </div>
            <!-- <div class="col-md-4">
                <button class="btn-add btn btn-success" style="margin-top: 30px;" data-toggle="modal" data-target="#modal-add-invoice" id="btn-add-invoice">Thêm hóa đơn</button>
            </div> -->
        </div>
    </div>

    <!-- Modal add -->
    <div class="modal fade" id="modal-add-invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <div class="modal-title font-bold text-success" id="exampleModalLabel">Thêm hóa đơn</div>
                    <button type="button" class="btn-close btn position-absolute">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 2rem"></i>
                    </button>

                </div>
                <div class="modal-body">
                    <div id="form-add-invoice-container" class="w-100 d-flex flex-column justify-content-center p-3">
                        <div class="d-flex form-group align-items-center justify-content-between">
                            <div class="font-weight-bold">Nhập số điện thoại khách hàng</div>
                            <input type="tel" class="form-control w-50" id="phone-customer" name="phone-customer" maxlength="10" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="d-flex form-group align-items-center justify-content-between">
                            <div class="font-weight-bold">Bạn đã kiểm tra khách hàng ?</div>
                            <button id="check-to-add-order" class="btn-success">OK</button>
                        </div>
                        <div class="d-flex form-group align-items-center justify-content-between">
                            <div class="font-weight-bold">Tên sản phẩm</div>
                            <select id="select-name-product" name="select-name-product" class="form-control w-50"></select>
                        </div>
                        <div class="d-flex form-group align-items-center justify-content-between">
                            <div class="font-weight-bold">Số lượng</div>
                            <input type="number" name="product-qty" id="product-qty" class="form-control w-50" min="1" max="999999" onchange="checkProductQty()">
                        </div>
                        <div class="d-flex form-group align-items-center justify-content-between">
                            <div class="font-weight-bold">Giá tiền</div>
                            <input type="number" name="price-product" id="price-product" class="form-control w-50">
                        </div>
                        <div class="d-flex form-group align-items-center justify-content-between">
                            <button id="order-update" value="Thêm" class="form-control w-30 btn-success"> Thêm</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" name="add_invoice" id="btn-add-invoice-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <table class="table table-bordered table-hover table-striped" style="margin-top: 20px;">
        <thead class="thead-light">
            <tr>
                <th style=" padding: 10px;font-size: 16px;">STT

                </th>
                <th style=" padding: 10px;font-size: 16px;">Họ & Tên
                    <button onclick="sortTable(1, true)" class="btn btn-primary btn-sm">▲</button>
                    <button onclick="sortTable(1, false)" class="btn btn-primary btn-sm">▼</button>
                </th>
                <th style=" padding: 10px;font-size: 16px;">SĐT
                    <button onclick="sortTable(2, true)" class="btn btn-primary btn-sm">▲</button>
                    <button onclick="sortTable(2, false)" class="btn btn-primary btn-sm">▼</button>
                </th>
                <th style=" padding: 10px;font-size: 16px;">Địa Chỉ
                    <button onclick="sortTable(3, true)" class="btn btn-primary btn-sm">▲</button>
                    <button onclick="sortTable(3, false)" class="btn btn-primary btn-sm">▼</button>
                </th>

                <th>Ngày Tạo
                    <button onclick="sortTable(4, true)" class="btn btn-primary btn-sm">▲</button>
                    <button onclick="sortTable(4, false)" class="btn btn-primary btn-sm">▼</button>
                </th>
                <th style=" padding: 10px;font-size: 16px; width: 130px; ">Hình thức thanh toán
                    <button onclick="sortTable(5, true)" class="btn btn-primary btn-sm">▲</button>
                    <button onclick="sortTable(5, false)" class="btn btn-primary btn-sm">▼</button>
                </th>

            </tr>
        </thead>
        <tbody>
            <?php
            $index = 0;
            foreach ($data as $item) {
                echo '<tr>
					<th>' . (++$index) . '</th>
					<td><a href="detail.php?id=' . $item['id'] . '">' . $item['cus_fullname'] . '</a></td>
					<td><a href="detail.php?id=' . $item['id'] . '">' . $item['cus_mobile'] . '</a></td>					
					<td>' . $item['cus_address'] . '</td>
					<td>' . $item['created_date'] . '</td>
					<td style="width: 50px " >';

                if ($item['payment_method'] == 0) {
                    echo  '<span class=" badge-pill badge-success">COD</span>';
                } elseif ($item['payment_method'] == 1) {
                    echo '<span class="badge-pill badge-info">BANK</span>';
                }

                echo '</td>
				</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</div>

<script type="text/javascript">
    function applyFilter() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;

        // Tạo URL mới với tham số start_date và end_date
        var url = window.location.pathname + '?';
        if (startDate !== '' && endDate !== '') {
            url += 'start_date=' + startDate + '&end_date=' + endDate;
        }
        // Chuyển hướng đến URL mới
        window.location.href = url;
    }
</script>
<script type="text/javascript">
    function changeStatus(id, status) {
        $.post('form_api.php', {
            'id': id,
            'status': status,
            'action': 'update_status'
        }, function(data) {
            if (data != null && data != '') {
                //alert(data);
                return;
            }
            location.reload
        })
    }
</script>

<script type="text/javascript">
    var listProduct = [];
    var listOrder=[];
    const phoneInput = document.getElementById('phone-customer');
    phoneInput.addEventListener('input', function(e) {
        // Chỉ cho phép nhập số
        this.value = this.value.replace(/\D/g, '');

        // Giới hạn độ dài 10 số
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const closeButton = document.querySelector("#modal-add-invoice .btn-close");

        closeButton.addEventListener("click", function(event) {
            event.preventDefault(); // Ngăn chặn hành động đóng modal ngay lập tức

            const confirmClose = confirm("Khi đóng toàn bộ dữ liệu sẽ bị xóa ?");
            if (confirmClose) {
                $("#modal-add-invoice").modal("hide"); // Đóng modal bằng jQuery
                // Xóa các class có thể gây lỗi
                setTimeout(() => {
                    $("#modal-add-invoice").removeClass("show in"); // Xóa class show & in
                    $(".modal-backdrop").remove(); // Xóa backdrop
                    $("body").removeClass("modal-open"); // Khôi phục trang web
                    $('#modal-add-invoice input, #modal-add-invoice select').val('');

                    $("#phone-customer").prop('disabled', false);
                    $("#select-name-product").prop('disabled', true);
                    $("#product-qty").prop('disabled', true);
                    listOrder=[];
                    listProduct=[];
                }, 300); // Đợi 300ms để đảm bảo modal đóng xong

            }
        });
    });

    function sortTable(columnIndex, ascending) {
        var table = document.querySelector('table');
        var rows = Array.from(table.querySelectorAll('tbody tr'));

        // Sắp xếp các hàng dựa trên giá trị của cột columnIndex
        rows.sort(function(rowA, rowB) {
            var valueA = rowA.cells[columnIndex].textContent.trim();
            var valueB = rowB.cells[columnIndex].textContent.trim();
            if (ascending) {
                return valueA.localeCompare(valueB);
            } else {
                return valueB.localeCompare(valueA);
            }
        });

        // Xóa tất cả các hàng trong bảng
        while (table.querySelector('tbody').firstChild) {
            table.querySelector('tbody').removeChild(table.querySelector('tbody').firstChild);
        }

        // Thêm lại các hàng đã sắp xếp vào bảng
        rows.forEach(function(row) {
            table.querySelector('tbody').appendChild(row);
        });
    }

    function checkProductQty() {
        let productQty = $("#product-qty").val();
        let productID= $("#select-name-product").val();
        if (productQty <= 0 || productQty == '') {
            alert('Số lượng không hợp lệ!');
            return;
        }
        // else if (listOrder.length > 0) {
        //     let foundItem = listOrder.find(item => item.product_id === productID);
            
        //     if (foundItem) {
        //         productQty += foundItem.quantity;
        //     }
        // }

        $.ajax({
            url: "form_api.php",
            method: "POST",
            data: {
                product_id: productID,
                product_qty: productQty,
                action: "priceProduct",
            },
            success: function(data) {
                data = JSON.parse(data);
                if(data.success){
                    $("#price-product").val(data.message);
                } else {
                    alert(data.message);
                }
            }
        })
        
    }

    $(document).ready(function() {
        //
        //Cập nhật thêm sản phẩm
        $('#order-update').on('click', function() {
            let productID = $("#select-name-product").val();
            let productQty = parseInt($("#product-qty").val(), 10);
            let productPrice = parseFloat($("#price-product").val());

            // Kiểm tra nếu giá trị nhập vào không hợp lệ
            if (!productID || isNaN(productQty) || isNaN(productPrice)) {
                alert("Vui lòng nhập đầy đủ và đúng định dạng sản phẩm!");
                return;
            }

            // Tìm sản phẩm có `product_id` tương ứng trong `listOrder`
            let foundItem = listOrder.find(item => item.product_id === productID);

            if (foundItem) {
                // Nếu sản phẩm đã có trong danh sách, hỏi người dùng có muốn thay đổi không
                if (confirm('Sản phẩm này đã có trong danh sách. Bạn có muốn cập nhật lại không?')) {    
                    foundItem.quantity = productQty;
                    foundItem.price = productPrice;
                }
            } else {
                // Nếu chưa có, thêm mới vào danh sách
                listOrder.push({ product_id: productID, quantity: productQty, price: productPrice });
            }

            console.log(listOrder); // Kiểm tra danh sách trên console
        });
        //
        $('#check-to-add-order').on('click', function() {
            let data = {
                phone_customer: $("#phone-customer").val(),
                action: "checkCus"
            }
            $.ajax({
                url: "form_api.php",
                method: "POST",
                data: data,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#select-name-product").prop('disabled', false);
                        $("#product-qty").prop('disabled', false);
                        // $("#price-product").prop('disabled', false);
                        $("#phone-customer").prop('disabled', true);
                        $.ajax({
                            url: "form_api.php",
                            method: "POST",
                            data: {
                                action: "getListProduct"
                            },
                            success: function(data) {
                                listProduct = JSON.parse(data);
                                console.log(listProduct);
                                listProduct.map((item, index) => {
                                    $("#select-name-product").append(`<option value=${item.id}>${item.id}-${item.name}</option>`);
                                })
                            }
                        })
                    } else {
                        alert(response.message); // Hiển thị thông báo lỗi
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra khi kiểm tra số điện thoại.");
                }
            });
        });
        $("#select-name-product").prop('disabled', true);
        $("#product-qty").prop('disabled', true);
        $("#price-product").prop('disabled', true);
        //Submit data
        $('#btn-add-invoice-submit').on("click", function() {
            $.ajax({
                url: "form_api.php",
                method: "POST",
                data: {
                    phone_customer: $("#phone-customer").val(),
                    listOrder: JSON.stringify(listOrder),  // 🔹 Chuyển listOrder thành JSON
                    action: "addInvoice"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        });
    });
</script>

<?php
require_once('../layouts/footer.php');
?>