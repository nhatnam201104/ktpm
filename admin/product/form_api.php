<?php
session_start();
require_once('../../utils/utility.php');
require_once('../../database/dbhelper.php');


$user = getUserToken();
if($user == null) {
	die();
}

if(!empty($_POST)) {
	$action = getPost('action');

	switch ($action) {
		case 'delete':
			deleteProduct();
			break;
	}
}

function deleteProduct() {
	$id = getPost('id');
	$status = getPost('status');
	$updated_at = date("Y-m-d H:i:s");
	$sql = "update Product set deleted = $status, updated_at = '$updated_at' where id = $id";
	execute($sql);

}