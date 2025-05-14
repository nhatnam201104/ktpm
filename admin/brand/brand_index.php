<?php
    $title = 'Quản Lý Thương Hiệu';
    $baseUrl = '../';
    require_once('../layouts/header.php');
    if ($_SESSION["user"]["role_id"] != 4 && $_SESSION["user"]["role_id"] != 2) {
        header("Location: ../index.php");
        die();
    }
    require_once($baseUrl.'../utils/utility.php');
    require_once($baseUrl.'../database/dbhelper.php');

    // Truy vấn lấy dữ liệu từ bảng 'brand'
    $sql = "SELECT * FROM brand WHERE deleted=0  ";

    $data = executeResult($sql);

?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 table-responsive">
        <h1 class="badge-pill badge-primary" style="display:flex;justify-content: center;padding: 10px;">Quản Lý Thương Hiệu</h1>
    </div>


        <a href="editor.php"><button class="btn btn-success">Thêm Thương Hiệu</button></a>

    
    <table class="table table-bordered table-hover table-striped" style="margin-top: 20px;">
        <thead class="thead-light">
            <tr>
            <th style="padding: 5px 5px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">STT</span>
                        <!-- Thêm nút lên và nút xuống cho cột Tên Thương Hiệu -->
                        <button onclick="sortTable(0, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(0, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <th style="padding: 5px 5px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Tên Thương Hiệu</span>
                        <!-- Thêm nút lên và nút xuống cho cột Tên Thương Hiệu -->
                        <button onclick="sortTable(1, true)" class="btn btn-primary btn-sm">▲</button>
                        <button onclick="sortTable(1, false)" class="btn btn-primary btn-sm">▼</button>
                    </div>
                </th>
                <th style="width: 50px;"></th>
                <th style="width: 50px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $index = 0;
                foreach($data as $item) {
                    echo '<tr>
                            <th>'.(++$index).'</th>
                            <td>'.$item['name'].'</td>
                            <td style="width: 50px">';
                    
                    // Kiểm tra quyền truy cập và hiển thị nút "Sửa"
                        echo '<a href="editor.php?id='.$item['id'].'"><button class="btn btn-warning">Sửa</button></a>';
                  

                    echo '</td>
                            <td style="width: 50px">';

                    // Kiểm tra quyền truy cập và hiển thị nút "Xóa"
                       
                        echo '<button onclick="deleteProduct('.$item['id'].')" class="btn btn-danger">Xoá</button>';
                    

                    echo '</td>
                        </tr>';
                }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
   function deleteProduct(id) {
    Swal.fire({
        title: 'Bạn chắc chắn muốn xoá sản phẩm này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Xoá',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Nếu người dùng nhấn vào nút Xoá, thực hiện xoá sản phẩm
            $.post('form_api.php', {
                'id': id,
                'action': 'delete'
            }, function(data) {
                location.reload();
            });
        }
    });
}

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
</script>

<?php
    require_once('../layouts/footer.php');
?>
