<?php
if(!empty($_POST)) {
    $id = getPost('id');
    $name = getPost('name');
    $created_date = $updated_at = date("Y-m-d H:i:s");
    if($id > 0) {
        //update
        $sql = "UPDATE category SET name = '$name', updated_at = '$updated_at' WHERE id = $id";
        execute($sql);
        echo '<div class="alert alert-success" role="alert">Chúc mừng bạn đã sửa danh mục thành công!</div>';
        die();
    } else {
        if($name == '') {
            die();
        }
        else{
            //insert
            $sql = "INSERT INTO category (name, created_date, updated_at) VALUES ('$name', '$created_date', '$updated_at')";
            execute($sql);
            echo '<div class="alert alert-success" role="alert">Chúc mừng bạn đã thêm danh mục thành công!</div>';
            die();
        }
    }
}
?>
