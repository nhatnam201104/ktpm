<link rel="stylesheet" type="text/css" href="../../assets/css/dashboard.css">
<?php


$title = 'Quản Lý Sản Phẩm';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
    echo 'Cannot access';
    die();
}
require_once($baseUrl . '../database/Database.php');
require_once($baseUrl . '../utils/utility.php');
require_once($baseUrl . '../database/dbhelper.php');
// Xử lý yêu cầu tìm kiếm

$sql = "SELECT Product.*, Category.name AS category_name, Brand.name AS brand_name 
	FROM Product 
	LEFT JOIN Category ON Product.category_id = Category.id
	LEFT JOIN Brand ON Product.brand_id = Brand.id
";

$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';

// Thêm điều kiện vào câu truy vấn SQL để tìm kiếm sản phẩm theo từ khóa
if ($search_keyword !== '') {
    $sql .= " AND (Product.name LIKE '%$search_keyword%' OR Brand.name LIKE '%$search_keyword%' OR Category.name LIKE '%$search_keyword%')";
}

// Xử lý lọc theo ngày
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Kiểm tra xem ngày bắt đầu và kết thúc có được cung cấp không
if ($start_date !== '' && $end_date !== '') {
    // Thêm điều kiện vào câu truy vấn SQL để lọc sản phẩm theo ngày
    $sql .= " AND Product.created_date BETWEEN '$start_date' AND '$end_date'";
}
$sql .= " order by product.id desc";


$data = executeResult($sql);



?>
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

function searchProduct() {
    var searchKeyword = document.getElementById('search_keyword').value.trim();

    // Tạo URL mới với tham số search_keyword
    var url = window.location.pathname + '?';
    if (searchKeyword !== '') {
        url += 'search_keyword=' + encodeURIComponent(searchKeyword);
    }
    // Chuyển hướng đến URL mới
    window.location.href = url;
}
</script>

<div class="row " style="margin-top: 20px;">
    <div class="col-md-12 table-responsive ">
        <h1 class=" badge-pill badge-primary" style="display:flex;justify-content: center;padding: 10px;">Quản Lý Sản
            Phẩm</h1>

        <div class="form-group">
            <input type="text" id="search_keyword" class="form-control" placeholder="Nhập từ khóa tìm kiếm..."
                onkeyup="searchTable()">
            <!-- <button onclick="searchProduct()" class="btn btn-primary mt-3">Tìm kiếm</button> -->
        </div>


    </div>
    <a href="add.php"><button class="btn btn-success">Thêm Sản Phẩm</button></a>



    <table class="table table-bordered table-hover table-striped" style="margin-top: 20px;">
        <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Thumbnail</th>
                <th style="padding: 5px 5px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Tên Sản Phẩm</span>
                        <!-- Thêm nút lên và nút xuống cho cột Tên Sản Phẩm -->
                        <button onclick="sortTable(2, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(2, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <!-- <th style="padding: 5px 5px;">
    <div style="display: flex; align-items: center;">
        <span style="margin-right: auto;">Nhãn hiệu</span>
        Thêm nút lên và nút xuống cho cột Nhãn hiệu
        <span><button onclick="sortTable(3, true)" class="btn btn-primary btn-sm">▲</button></span>
        <button onclick="sortTable(3, false)" class="btn btn-primary btn-sm">▼</button>
    </div>
</th> -->
                <th style="padding: 5px 5px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Số lượng tồn</span>
                        <!-- Thêm nút lên và nút xuống cho cột Số lượng tồn -->
                        <button onclick="sortTable(4, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(4, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <th style="padding: 10px 20px; ">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Mô tả</span>
                        <!-- Thêm nút lên và nút xuống cho cột Mô tả -->
                        <button onclick="sortTable(5, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(5, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <th style="padding: 10px 20px; font-size: 16px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Giá nhập</span>
                        <!-- Thêm nút lên và nút xuống cho cột Giá nhập -->
                        <button onclick="sortTable(6, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(6, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <th style=" padding: 10px 20px;font-size: 16px;">Giá bán
                    <!-- Thêm nút lên và nút xuống cho cột Tên Sản Phẩm -->
                    <button onclick="sortTable(7, true)" class="btn btn-primary btn-sm">▲</button>
                    <button onclick="sortTable(7, false)" class="btn btn-primary btn-sm">▼</button>
                </th>
                <th style="padding: 10px 20px; font-size: 16px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Danh Mục</span>
                        <!-- Thêm nút lên và nút xuống cho cột Danh Mục -->
                        <button onclick="sortTable(8, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(8, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <th style="width: 50px"></th>
                <th style="width: 50px"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 0;
            foreach ($data as $item) {
                $price_product = "WITH LatestEntry AS (
                        SELECT 
                            ed.product_id,
                            MIN(ec.enter_day) AS min_enter_day
                        FROM enter_coupon ec
                        JOIN entry_details ed ON ec.id = ed.entercoupon_id
                        WHERE ec.status = 0 and ed.p_inventory>0
                        GROUP BY ed.product_id
                    )
                    SELECT 
                        product.*,
                        ed.enter_price as enter_price,
                        (ed.enter_price * (1 +( ed.profit_margin/100))) - 
                        ((ed.enter_price * (1 +( ed.profit_margin/100))) * COALESCE(discount.discount_percentage, 0) / 100) AS price,
                        ed.p_inventory as p_inventory
                    FROM product
                    JOIN LatestEntry ON product.id = LatestEntry.product_id
                    JOIN enter_coupon ec ON ec.enter_day = LatestEntry.min_enter_day
                    JOIN entry_details ed ON ec.id = ed.entercoupon_id AND ed.product_id = product.id
                    LEFT JOIN discount ON discount.id = product.discount_id
                    WHERE ed.p_inventory > 0 AND product.id=$item[id]";
                $data2 = Database::getInstance()->execute($price_product);
                $row1 = $data2->fetch_assoc();
                if (mysqli_num_rows($data2) == 0) {
                    $row1['enter_price'] = 0;
                    $row1['price'] = 0;
                    $row1['p_inventory'] = 0;

                }
                echo '<tr>
                <th>' . (++$index) . '</th>
                <td><img src="' . ($item['featured_image']) . '" style="height: 100px"/></td>
                <td>' . $item['name'] . '</td>
                <td>' . number_format($row1['p_inventory']) . '</td>
                <td>' . $item['description'] . '</td>
                <td>' . number_format($row1['enter_price']) . ' VNĐ</td>
                <td>' . number_format($row1['price']) . ' VNĐ</td>
                <td>' . $item['category_name'] . '</td>
                <td style="width: 50px">';


                echo '<a href="editor.php?id=' . $item['id'] . '"><button class="btn btn-warning mb-1">Sửa</button></a>
                <a href="upload.php?id=' . $item['id'] . '"><button class="btn btn-primary">Upload Image</button></a>
        ';


                echo '</td>
            <td style="width: 50px">';

                if ($item['deleted'] == 1) {
                    echo "<button onclick=\"deleteProduct(" . $item['id'] . ",0)\" class='del-supplier-data btn btn-danger' name='delete_data'>Đã Khóa</button>";
                } else {
                    echo "<button onclick=\"deleteProduct(" . $item['id'] . ",1)\" class='del-supplier-data btn btn-success' name='delete_data'>Đang Hoạt Động</button>";

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
function deleteProduct(id, status) {
    Swal.fire({
        title: 'Bạn chắc chắn muốn thay đổi trạng thái sản phẩm này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Nếu người dùng nhấn vào nút Xoá, thực hiện xoá sản phẩm
            $.post('form_api.php', {
                'id': id,
                'status': status,
                'action': 'delete'
            }, function(data) {
                location.reload();
            });
        }
    });
}
</script>
<script type="text/javascript">
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

function searchTable() {
    let input = document.getElementById("search_keyword").value.toLowerCase();
    let table = document.querySelector("table tbody");
    let rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        let phoneCell = rows[i].getElementsByTagName("td")[1]; // Cột SĐT (chỉ số 2)
        if (phoneCell) {
            let phoneText = phoneCell.textContent || phoneCell.innerText;
            rows[i].style.display = phoneText.toLowerCase().includes(input) ? "" : "none";
        }
    }
}
</script>

<?php
require_once('../layouts/footer.php');
?>