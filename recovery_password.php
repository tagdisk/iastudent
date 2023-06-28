<?php 
	define('META', ['location' => 'Recuperaci&oacute;n de contrase&ntilde;a']);
	require 'templates/header.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'drivers/PHPMailer/Exception.php';
	require 'drivers/PHPMailer/PHPMailer.php';
	require 'drivers/PHPMailer/SMTP.php';

	if($active_session){
		header('location: '.SERVER['url'].'welcome.php');
		die();
	}

	$next = isset($_SESSION[strtolower(SERVER['title'])]['next']) ? ($_SESSION[strtolower(SERVER['title'])]['next']) : 1;

	if(!empty($_POST)){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			if($next == 1){
				$email = strtolower($mysqli->real_escape_string($_POST['email']));
				
				if(empty($email)){
					$message[] = "Todos los campos son obligatorios.";
	
				}elseif(!isEmail($email)){
					$message[] = "Por favor, escribe una direcci&oacute;n de correo v&aacute;lida.";
	
				}elseif(!emailExiste($email)){
					$message[] = "Correo electr&oacute;nico: $email, no existente. Por favor, verifique de nuevo.";
	
				}
				
				if(count($message) == 0){
					$code = randomInt(6);
	
					if(codePassword($code, $email)){
						$strEmail = explode('@', $email);
						$substrEmail = substr($strEmail[0], 0, 3).'****'.substr($strEmail[0], -2).'@'.$strEmail[1];
			
						$sql = "SELECT name,surname,user FROM users WHERE email='$email' LIMIT 1";
						$result = $mysqli->query($sql);
						$data_user = $result->fetch_assoc();

						$mail = new PHPMailer(true);
						$mail->setLanguage('es', 'PHPMailer/language/');
		
						try {
							$mail->CharSet = 'UTF-8';
							$mail->SMTPDebug = 0;
							$mail->isSMTP();  
							$mail->Host = 'smtp.gmail.com';
							$mail->SMTPAuth = true;
							$mail->Username = 'hernandezjose0901@gmail.com';
							$mail->Password = 'raqghdduoitlxjfs';
							$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
							$mail->Port = 465;
						
							$mail->setFrom('hernandezjose0901@gmail.com', SERVER['title']);
							$mail->addAddress($email, $data_user['user']);
					
							$mail->isHTML(true);
							$mail->Subject = html_entity_decode('Recuperaci&oacute;n de contrase&ntilde;a');
							$mail->Body = '<strong>C&oacute;digo de Verificaci&oacute;n</strong><p>Hola '.$data_user['name'].' '.$data_user['surname'].', tu c&oacute;digo de verificaci&oacute;n para recuperar tu cuenta es: <strong><h1>'.$code.'</h1></strong></p><p>Si no has solicitado este c&oacute;digo en <a href="'.SERVER['url'].'" target="_blank">'.SERVER['url'].'</a>, ignore este mensaje.</p><small>© 2023 '.SERVER['title'].'. Todos los derechos reservados.</small>';
					
							$mail->send();
							$success = true;

							$_SESSION[strtolower(SERVER['title'])]['email'] = $email;
							$_SESSION[strtolower(SERVER['title'])]['next'] = 2;
							$next = 2;

							$message[] = 'Se ha enviado un c&oacute;digo de 6 d&iacute;gitos al correo electr&oacute;nico: <span class="fw-semibold">'.$substrEmail.'</span>. Por favor, ingrese el c&oacute;digo para verificar.';
							
						}catch(Exception $e){
							$message[] = 'Error a enviar c&oacute;digo de recuperaci&oacute;n al correo electr&oacute;nico: <span class="fw-semibold">'.$substrEmail.'</span>. Por favor, verifique su conexi&oacute;n a Internet.';
						}
					}
					
				}

			}elseif($next == 2){
				$code = $mysqli->real_escape_string($_POST['code']);
				$email = $_SESSION[strtolower(SERVER['title'])]['email'];

				$sql = "SELECT code_email,code_expire FROM users WHERE email='$email' LIMIT 1";
				$result = $mysqli->query($sql);
				$data_user = $result->fetch_assoc();

				$date = new DateTime($data_user['code_expire']);
				$date->modify('3 minute');
				$expire = $date->format('Y-m-d H:i:s');

				$date_new = new DateTime('now');
				$time = $date_new->format('Y-m-d H:i:s');

				if(empty($code)){
					$message[] = "Todos los campos son obligatorios.";
	
				}elseif(!validaPassword($code, $data_user['code_email'])){
					$message[] = "El c&oacute;digo ingresado es inv&aacute;lido.";

				}elseif($time > $expire){
					$message[] = 'C&oacute;digo de verificaci&oacute;n expirado. Por favor, intente de nuevo. <p class="m-0 mt-3"><a href="'.SERVER['uri'].'" class="btn btn-primary btn-sm">Intentar de nuevo</a></p>';

					session_reset();
					session_destroy();
				}

				
				if(count($message) == 0){
					$_SESSION[strtolower(SERVER['title'])]['next'] = 3;
					$next = 3;

					$success = true;
					$message[] = "C&oacute;digo de verificaci&oacute;n correcto. Ahora puedes actualizar tu contrase&ntilde;a.";
				}

			}elseif($next == 3){
				$password = $mysqli->real_escape_string($_POST['password']);	
				$passwordVerify = $mysqli->real_escape_string($_POST['passwordVerify']);	
				$email = $_SESSION[strtolower(SERVER['title'])]['email'];

				$sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
				$result = $mysqli->query($sql);
				$data_user = $result->fetch_assoc();

				if(empty($password) OR empty($passwordVerify)){
					$message[] = "Todos los campos son obligatorios.";
	
				}elseif(!validaPassword($password, $passwordVerify)){
					$message[] = "Las nuevas contrase&ntilde;as no coinciden.";
				}

				if(count($message) == 0){
					$hash = hashPassword($password);
	
					$return = cambiaPassword($hash, $data_user['id']);
	
					if($return > 0){
						session_reset();
						session_destroy();
						header('location: '.SERVER['url']);
						die();
					}
	
				}
			}

		}//End POST
	}	
?>
<section class="container">
	<div class="row justify-content-center">
		<div class="card px-0 shadow col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-5">
			<div class="card-header fs-4 fw-semibold text-bg-primary bg-opacity-75"><?php echo META['location']; ?></div>
		
			<div class="card-body py-4">
				<form class="needs-validation" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" role="form" autocomplete="on" novalidate="true">
					<fieldset class="<?php echo ($next == 1) ? '' : 'd-none'; ?>" <?php echo ($next == 1) ? '' : 'disabled'; ?>>
						<div class="form-floating mb-3">
							<input type="email" class="form-control" id="email" name="email" placeholder="" required="true">
							<label for="email" class="form-label"><i class="ias ias-envelope-at ias-hc-lg me-1"></i>Correo electr&oacute;nico:</label>
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
						
						<div class="text-end">
							<button class="mb-2 btn btn-success text-uppercase" type="submit">Enviar c&oacute;digo<i class="ias ias-send ias-hc-lg ms-1"></i></button>
						</div>
					</fieldset>

					<fieldset class="<?php echo ($next == 2) ? '' : 'd-none'; ?>" <?php echo ($next == 2) ? '' : 'disabled'; ?>>
						<div class="form-floating mb-3">
							<input type="text" class="form-control" id="code" name="code" placeholder="" minlength="6" maxlength="6" required="true">
							<label for="code" class="form-label"><i class="ias ias-code ias-hc-lg me-1"></i>Ingrese el c&oacute;digo:</label>
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
						
						<div class="text-end">
							<button class="mb-2 btn btn-primary text-uppercase" type="submit">Verificar</button>
						</div>
					</fieldset>

					<fieldset class="<?php echo ($next == 3) ? '' : 'd-none'; ?>" <?php echo ($next == 3) ? '' : 'disabled'; ?>>
						<div class="row g-3">
							<div class="col-12">
								<label for="password" class="form-label"><i class="ias ias-person-lock ias-hc-lg me-1"></i>Contrase&ntilde;a nueva:</label>
								<input type="password" class="form-control" id="password" name="password" minlength="6" maxlength="60" required="true">
								<div class="invalid-feedback">Este campo es obligatorio.</div>
							</div>
		
							<div class="col-12">
								<label for="passwordVerify" class="form-label"><i class="ias ias-unlock ias-hc-lg me-1"></i>Confirme contrase&ntilde;a nueva:</label>
								<input type="password" class="form-control" id="passwordVerify" name="passwordVerify" minlength="6" maxlength="60" required="true">
								<div class="invalid-feedback">Este campo es obligatorio.</div>
							</div>
			
							<div class="text-end">
								<button class="mb-2 btn btn-primary text-uppercase" type="submit">Actualizar</button>
							</div>
						</div>
					</fieldset>

					<hr class="my-3">
				
					<h2 class="fs-5 fw-bold mb-3"><i class="ias ias-ui-radios me-1"></i>Otras opciones</h2>
					<div class="dropdown-menu position-static d-grid bg-transparent p-0 border-0">
						<a class="dropdown-item rounded gap-2 py-2" href="<?php echo SERVER['url']; ?>"><i class="ias ias-door-closed ias-hc-lg me-1"></i>Ya estoy registrado, iniciar sesi&oacute;n</a>

						<a class="dropdown-item rounded gap-2 py-2" href="<?php echo SERVER['url'].'signin.php'; ?>"><i class="ias ias-person-add ias-hc-lg me-1"></i>¿Eres nuevo? Reg&iacute;strate</a>
					</div>

				</form>
			</div>
		</div>
	</div>
</section>

<?php require 'templates/footer.php'; ?>