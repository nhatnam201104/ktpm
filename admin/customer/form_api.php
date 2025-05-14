<?php
session_start();
require_once('../../utils/utility.php');
require_once('../../database/dbhelper.php');

$user = getUserToken();
if($user == null) {
	die();
}

if(!empty($_POST)) {
	$action = getPOST('action');

	switch ($action) {
		case 'delete':
			deleteUser();
			break;
		case 'toggle':
			statusUser();
			break;
	}
}

// function deleteUser() {
// 	$id = getPOST('id');
// 	$updated_at = date("Y-m-d H:i:s");
// 	$sql="DELETE FROM user WHERE id=$id";
// 	execute($sql);
// }

function statusUser() {
	$id = getPOST('id');
	$val=getPOST('val');
	$updated_at = date("Y-m-d H:i:s");
	$sql = "update user set is_active = $val, updated_at = '$updated_at' where id = $id";
	execute($sql);
}