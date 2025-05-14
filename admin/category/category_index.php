<?php
    $title = 'Quản Lý Danh Mục';
    $baseUrl = '../';
    require_once('../layouts/header.php');
    if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
        echo 'Cannot access';
        die();
    }
    require_once($baseUrl.'../utils/utility.php');
    require_once($baseUrl.'../database/dbhelper.php');

    // Truy vấn lấy dữ liệu từ bảng 'category'
    $sql = "SELECT * FROM category";

    $data = executeResult($sql);

?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 table-responsive">
        <h1 class="badge-pill badge-primary" style="display:flex;justify-content: center;padding: 10px;">Quản Lý Danh Mục</h1>
    </div>

    <?php
            echo '<a href="editor.php"><button class="btn btn-success">Thêm Danh Mục</button></a>';
    ?>
    <table class="table table-bordered table-hover table-striped" style="margin-top: 20px;">
        <thead class="thead-light">
            <tr>
                <th style="padding: 5px 5px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">STT</span>
                    </div>
                </th>
                <th style="padding: 5px 5px;">
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: auto;">Tên Danh Mục</span>
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
                    
                    echo '<a href="editor.php?id='.$item['id'].'"><button class="btn btn-warning">Sửa</button></a>';


                    echo '</td>
                            <td style="width: 50px">';

                        if ($item['deleted'] == 1) {
                            echo"<button onclick=\"deleteCategory(".$item['id'].",0)\" class='del-supplier-data btn btn-danger' name='delete_data'>Khóa</button>";
                        } else {
                            echo"<button onclick=\"deleteCategory(".$item['id'].",1)\" class='del-supplier-data btn btn-success' name='delete_data'>Xác Nhận</button>";

                        }
                    


                    echo '</td>
                        </tr>';
                }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function deleteCategory(id,deleted) {
        Swal.fire({
            title: 'Bạn chắc chắn muốn xoá danh mục này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng nhấn vào nút Xoá, thực hiện xoá danh mục
                $.post('form_api.php', {
                    'id': id,
                    'status':deleted,
                    'action': 'delete'
                }, function(data) {
                    location.reload();
                });
            }
        });
    }
</script>

<?php
    require_once('../layouts/footer.php');
?>
