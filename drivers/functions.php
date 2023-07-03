<?php
	
function isEmail($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)){
		return true;
		} else {
		return false;
	}
}

function validaPassword($var1, $var2){
	if (strcmp($var1, $var2) !== 0){
		return false;
		} else {
		return true;
	}
}

function minMax($min, $max, $valor){
	if(strlen(trim($valor)) < $min)
	{
		return true;
	}
	else if(strlen(trim($valor)) > $max)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function usuarioExiste($usuario){
	global $mysqli;
	
	$stmt = $mysqli->prepare("SELECT id FROM users WHERE user=? LIMIT 1");
	$stmt->bind_param('s', $usuario);
	$stmt->execute();
	$stmt->store_result();
	$num = $stmt->num_rows;
	$stmt->close();
	
	if($num > 0){
		return true;
		} else {
		return false;
	}
}

function emailExiste($email){
	global $mysqli;
	
	$stmt = $mysqli->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();
	$num = $stmt->num_rows;
	$stmt->close();
	
	if ($num > 0){
		return true;
		} else {
		return false;	
	}
}

function generateToken(){
	$gen = md5(uniqid(mt_rand(), false));	
	return $gen;
}

function hashPassword($password){
	$hash = password_hash($password, PASSWORD_DEFAULT);
	return $hash;
}

function filterChar($in){
	$filter = str_replace('&', '&amp;', $in);
	$filter = str_replace('á', '&aacute;', $filter);
	$filter = str_replace('é', '&eacute;', $filter);
	$filter = str_replace('í', '&iacute;', $filter);
	$filter = str_replace('ó', '&oacute;', $filter);
	$filter = str_replace('ú', '&uacute;', $filter);
	$filter = str_replace('Á', '&Aacute;', $filter);
	$filter = str_replace('É', '&Eacute;', $filter);
	$filter = str_replace('Í', '&Iacute;', $filter);
	$filter = str_replace('Ó', '&Oacute;', $filter);
	$filter = str_replace('Ú', '&Uacute;', $filter);
	$filter = str_replace('ñ', '&ntilde;', $filter);
	$filter = str_replace('Ñ', '&Ntilde;', $filter);
	$filter = str_replace('¡', '&#161;', $filter);
	$filter = str_replace('!', '&#33;', $filter);
	$filter = str_replace('+', '&#43;', $filter);
	$filter = str_replace('-', '&#45;', $filter);

	return $filter;
}

function resultBlock($message, $type = 'warning'){
	$icon = match($type){
		'success' => 'patch-check-fill',
		default => 'exclamation-triangle-fill' 
	};

	if(count($message) > 0){
		echo '<div class="d-flex fixed-top justify-content-center mt-4"><div class="col-6"><div id="error" class="alert alert-'.$type.' alert-dismissible fade show" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><div><i class="ias ias-'.$icon.' ias-hc-lg me-1"></i>'.$message[0].'</div></div></div></div>';
	}
}

function registraUsuario($array){
	global $mysqli;
	
	$stmt = $mysqli->prepare("INSERT INTO users (name, surname, gender, user, dni_prefix, dni, email, password, created) VALUES(?,?,?,?,?,?,?,?,NOW())");
	$stmt->bind_param('ssssssss', $array['name'], $array['surname'], $array['gender'], $array['user'], $array['dni_prefix'], $array['dni'], $array['email'], $array['password']);
	
	if ($stmt->execute()){
		return $mysqli->insert_id;
		} else {
		return 0;	
	}
}

function editarUsuario($array){
	global $mysqli;

	$stmt = $mysqli->prepare("UPDATE users SET name=?, surname=?, gender=?, user=?, dni_prefix=?, dni=?, email=?, updated=NOW() WHERE id=?");
	$stmt->bind_param('ssssssss', $array['name'], $array['surname'], $array['gender'], $array['user'], $array['dni_prefix'], $array['dni'], $array['email'], $array['id']);

	if ($stmt->execute()){
		$stmt->close();
		return true;
		} else {
		$stmt->close();
		return false;	
	}
}


function isNullLogin($usuario, $password){
	if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1)
	{
		return true;
	}
	else
	{
		return false;
	}		
}

function login($usuario, $password, $session){
	global $mysqli;

	$stmt = $mysqli->prepare("SELECT id, password FROM users WHERE user=? OR email=? LIMIT 1");
	$stmt->bind_param('ss', $usuario, $usuario);
	$stmt->execute();
	$stmt->store_result();
	$rows = $stmt->num_rows;
	
	if($rows > 0){
		$stmt->bind_result($id, $passwd);
		$stmt->fetch();
		$stmt->close();

		if(password_verify($password, $passwd)){
			lastSession($id, $session);
			$_SESSION[strtolower(SERVER['title'])]['id_user'] = $id;
			$_SESSION[strtolower(SERVER['title'])]['active'] = true;

			header('location: welcome.php');

		}else{
			$errors = "La contrase&ntilde;a es incorrecta.";
		}
		

	}else{
		$errors = "El nombre de usuario o correo electr&oacute;nico es inv&aacute;lido.";
	}

	return $errors;
}

function lastSession($id, $session){
	global $mysqli;
	
	$stmt = $mysqli->prepare("UPDATE users SET last_session=NOW(), session=? WHERE id=?");
	$stmt->bind_param('ss', $session, $id);
	$stmt->execute();
	$stmt->close();
}


function cambiaPassword($password, $user_id){
	global $mysqli;
	
	$stmt = $mysqli->prepare("UPDATE users SET password=?, updated=NOW(), session='0', code_email=NULL, code_expire=NULL WHERE id=?");
	$stmt->bind_param('ss', $password, $user_id);
	
	if($stmt->execute()){
		return true;
		} else {
		return false;		
	}
}

function randomInt($length = 10) {
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
} 

function codePassword($code, $email){
	global $mysqli;
	
	$stmt = $mysqli->prepare("UPDATE users SET code_email=?, code_expire=NOW() WHERE email=? LIMIT 1");
	$stmt->bind_param('ss', $code, $email);
	
	if($stmt->execute()){
		return true;
		} else {
		return false;		
	}
}


?>