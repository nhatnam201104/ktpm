<?php
    $name = 'Thêm/Sửa Thương Hiệu';
    $baseUrl = '../';
    require_once('../layouts/header.php');
    if ($_SESSION["user"]["role_id"] != 4 && $_SESSION["user"]["role_id"] != 2) {
        header("Location: ../index.php");
        die();
    }
    $id = $name = '';
    require_once('form_save.php');
  
    $id = getGet('id');
    if($id != '' && $id > 0) {
        $sql = "SELECT * FROM brand WHERE id = '$id'  ";
        $brandItem = executeResult($sql, true);
        if($brandItem != null) {
            $name = $brandItem[0]['name'];
        } else {
            $id = 0;
        }
    } else {
        $id = 0;
    }
?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 table-responsive">
        <h3>Thêm/Sửa Thương Hiệu</h3>
        <div class="panel panel-primary">
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label for="name">Tên Thương Hiệu:</label>
                        <input required="true" type="text" class="form-control" id="name" name="name" value="<?=$name?>">
                        <input type="text" name="id" value="<?=$id?>" hidden="true">
                    </div>
                    <button class="btn btn-success">Lưu Thương Hiệu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    require_once('../layouts/footer.php');
?>
