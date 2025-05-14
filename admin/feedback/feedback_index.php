<link rel="stylesheet" type="text/css" href="../../assets/css/dashboard.css">
<?php

$title = 'Quản lý phản hồi';
$baseUrl =  '../';
require_once('../layouts/header.php');

$sql = "SELECT product.name as name, comment.* FROM comment 
join product on product.id=comment.product_id
ORDER BY comment.status ASC, comment.created_date DESC";
$data = executeResult($sql);
?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 table-responsive">
        <h1 class="badge-pill badge-primary" style="display:flex;justify-content: center;padding: 10px;">Quản Lý Phản Hồi</h1>
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead-light">
                <tr>
                    <th>STT</th>
                    <th style="padding: 5px 5px;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: auto;">Tên</span>
                            <button onclick="sortTable(1, true)" class="btn btn-primary btn-sm">▲</button>
                            <button onclick="sortTable(1, false)" class="btn btn-primary btn-sm">▼</button>
                        </div>
                    </th>
                    <th style="padding: 5px 5px;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: auto;">Email</span>
                            <button onclick="sortTable(2, true)" class="btn btn-primary btn-sm">▲</button>
                            <button onclick="sortTable(2, false)" class="btn btn-primary btn-sm">▼</button>
                        </div>
                    </th>
                    <th style="padding: 5px 5px;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: auto;">Star</span>
                            <button onclick="sortTable(3, true)" class="btn btn-primary btn-sm">▲</button>
                            <button onclick="sortTable(3, false)" class="btn btn-primary btn-sm">▼</button>
                        </div>
                    </th>
                    <th style="padding: 5px 5px;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: auto;">Sản Phẩm</span>
                            <button onclick="sortTable(3, true)" class="btn btn-primary btn-sm">▲</button>
                            <button onclick="sortTable(3, false)" class="btn btn-primary btn-sm">▼</button>
                        </div>
                    </th>
                    <th style="padding: 10px 20px;">Nội dung</th>
                    <th style="padding: 5px 5px;">
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: auto;">Ngày tạo</span>
                            <button onclick="sortTable(5, true)" class="btn btn-primary btn-sm">▲</button>
                            <button onclick="sortTable(5, false)" class="btn btn-primary btn-sm">▼</button>
                        </div>
                    </th>
                    <th style="width: 130px">Tình trạng</th>
                    <th style="width: 130px">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 0;
                foreach ($data as $item) {
                    echo '<tr>
                        <td>' . (++$index) . '</td>
                        <td>' . $item['fullname'] . '</td>
                        <td>' . $item['email'] . '</td>         
                        <td>' . $item['star'] . '</td>
                        <td>' . $item['name'] . '</td>
                        <td>' . $item['description'] . '</td>
                        <td>' . $item['created_date'] . '</td>
                        <td style="width: 50px">';
                    if ($item['status'] == 0) {
                        echo '<button onclick="markRead(' . $item['id'] . ')" class="btn btn-danger">Đánh dấu đã đọc</button>';
                    } else {
                        // Display green label if status is 1 (read)
                        echo '<span class="label label-success">Đã đọc</span>';
                    }
                    echo '</td>
                        <td style="width: 50px">';
                    echo '<button onclick="deleteComment(' . $item['id'] . ')" class="btn btn-danger">Xóa bình luận</button></td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function deleteComment(id) {
        if (confirm('Bạn có chắc chắn muốn xóa bình luận này không?')) {
            $.post('form_api.php', {
                'id': id,
                'action': 'delete'
            }, function(data) {
                location.reload()
            })
        }
    }

    function markRead(id) {
        $.post('form_api.php', {
            'id': id,
            'action': 'mark'
        }, function(data) {
            location.reload()
        })
    }

    function sortTable(columnIndex, ascending) {
        var table = document.querySelector('table');
        var rows = Array.from(table.querySelectorAll('tbody tr'));

        rows.sort(function(rowA, rowB) {
            var valueA = rowA.cells[columnIndex].textContent.trim();
            var valueB = rowB.cells[columnIndex].textContent.trim();
            if (ascending) {
                return valueA.localeCompare(valueB);
            } else {
                return valueB.localeCompare(valueA);
            }
        });

        while (table.querySelector('tbody').firstChild) {
            table.querySelector('tbody').removeChild(table.querySelector('tbody').firstChild);
        }

        rows.forEach(function(row) {
            table.querySelector('tbody').appendChild(row);
        });
    }
</script>

<?php
require_once('../layouts/footer.php');
?>