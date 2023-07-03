<?php
	date_default_timezone_set('America/Caracas');
    
    define('SERVER', [
        'title' => 'IAstudent',
        'url' => 'http://localhost/iastudent/',
        'uri' => $_SERVER["REQUEST_URI"],
    ]);

	$mysqli  = new mysqli(
		'localhost',
		'root',
		'',
		'iastudent'
	);
	
	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}

	$message = array();
	session_start();

	$active_session = isset($_SESSION[strtolower(SERVER['title'])]['active']) ? ($_SESSION[strtolower(SERVER['title'])]['active'] == true) ? true : false : false;

	if($active_session){
		$id = $_SESSION[strtolower(SERVER['title'])]['id_user'];
		$sql = "SELECT * FROM users WHERE id='$id' LIMIT 1";
		$result = $mysqli->query($sql);
		$data_user = $result->fetch_assoc();
	}
?>