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
		case 'mark':
			deleteUser();
			break;
		case 'delete':
			deleteComment();
			break;
	}
}

function deleteUser() {
	$id = getPost('id');
	$updated_at = date("Y-m-d H:i:s");
	$sql = "update comment set status = 1  where id = $id";
	execute($sql);
}

function deleteComment() {
	$id = getPost('id');
	$sql = "delete from comment where id = $id";
	execute($sql);
}