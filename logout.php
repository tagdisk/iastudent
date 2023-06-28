<?php
	require 'drivers/connection.php';

	$stmt = $mysqli->prepare("UPDATE users SET session='0', code_email=NULL, code_expire=NULL WHERE id=?");
	$stmt->bind_param('s', $data_user['id']);
	$stmt->execute();
	$stmt->close();

	session_reset();
	session_destroy();

	header('location: '.SERVER['url']);
	die();
?>