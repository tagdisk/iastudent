<?php 
	define('META', ['location' => 'Registrar']);
	require 'templates/header.php'; 

	if($active_session){
		header('location: '.SERVER['url'].'welcome.php');
		die();
	}

	if(!empty($_POST)){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){

			$name = ucwords($mysqli->real_escape_string($_POST['name']));
			$surname = ucwords($mysqli->real_escape_string($_POST['surname']));
			$gender = $mysqli->real_escape_string($_POST['gender']);
			$user = $mysqli->real_escape_string($_POST['user']);
			$DNIprefix = $mysqli->real_escape_string($_POST['prefix']);
			$dni = $mysqli->real_escape_string($_POST['dni']);	
			$email = strtolower($mysqli->real_escape_string($_POST['email']));	
			$password = $mysqli->real_escape_string($_POST['password']);	
			$passwordVerify = $mysqli->real_escape_string($_POST['passwordVerify']);	
			$eula = $mysqli->real_escape_string($_POST['eula']);


			if(empty($name) OR empty($surname) OR empty($gender) OR empty($user) OR empty($DNIprefix) OR empty($dni) OR empty($email) OR empty($password) OR empty($passwordVerify) OR empty($eula)){
				$message[] = "Todos los campos son obligatorios.";

			}elseif(!isEmail($email)){
				$message[] = "Por favor, escribe una direcci&oacute;n de correo v&aacute;lida.";

			}elseif(usuarioExiste($user)){
				$message[] = "El nombre de usuario: $user, ya est&aacute; en uso.";

			}elseif(emailExiste($email)){
				$message[] = "El correo electr&oacute;nico: $email, ya est&aacute; en uso.";

			}elseif(!validaPassword($password, $passwordVerify)){
				$message[] = "Las contrase&ntilde;as no coinciden.";
			}
			

			if(count($message) == 0){
				$hash = hashPassword($password);

				$strGender = match($gender){
					'1' => 'man',
					'2' => 'woman',
					default => 'man'
				};

				$strDNIPrefix = match($DNIprefix){
					'1' => 'V',
					'2' => 'E',
					default => 'V'
				};

				$data = array(
					'name' => filterChar($name),
					'surname' => filterChar($surname),
					'gender' => $strGender,
					'user' => filterChar($user),
					'dni_prefix' => $strDNIPrefix,
					'dni' => $dni,
					'email' => $email,
					'password' => $hash
				);

				$return = registraUsuario($data);

				if($return > 0){
					$success = true;
					$message[] = "Guardado con &eacute;xito.";
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
					<div class="row g-3">
	
						<div class="col-6">
							<label for="name" class="form-label"><i class="ias ias-person-lines-fill ias-hc-lg me-1"></i>Nombre/s:</label>
	
							<input type="text" class="form-control text-capitalize" id="name" name="name" minlength="3" maxlength="50" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-6">
							<label for="surname" class="form-label"><i class="ias ias-person-lines-fill ias-hc-lg me-1"></i>Apellido/s:</label>
							<input type="text" class="form-control text-capitalize" id="surname" name="surname" minlength="3" maxlength="50" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-6">
							<label for="gender" class="form-label"><i class="ias ias-gender-ambiguous ias-hc-lg me-1"></i>Indica tu g&eacute;nero:</label>
							<select class="form-select" id="gender" name="gender" required="true">
								<option value="" disabled selected>Elige una opci&oacute;n...</option>
								<option value="1">Hombre</option>
								<option value="2">Mujer</option>
							</select>
							<div class="invalid-feedback">Este campo es obligatorio.</div>
	
						</div>
	
						<div class="col-6">
							<label for="user" class="form-label"><i class="ias ias-person-check ias-hc-lg me-1"></i>Nombre de usario:</label>
							<input type="text" class="form-control" id="user" name="user" minlength="3" maxlength="20" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						
						<div class="col-12">
							<label for="dni" class="form-label"><i class="ias ias-postcard ias-hc-lg ias-hc-flip-horizontal me-1"></i>C&eacute;dula de identificaci&oacute;n:</label>
							<div class="row gx-1">
								<div class="col-3">
									<select class="form-select rounded-0 rounded-start" name="prefix" required="true">
										<option value="1" selected>V</option>
										<option value="2">E</option>
									</select>
									<div class="invalid-feedback">Este campo es obligatorio.</div>
								</div>
	
								<div class="col-9">
									<input type="text" id="dni" class="form-control rounded-0 rounded-end" name="dni" minlength="1" maxlength="8" required="true">
									<div class="invalid-feedback">Este campo es obligatorio.</div>
								</div>
	
							</div>
						</div>
						
						<div class="col-12">
							<label for="email" class="form-label"><i class="ias ias-envelope-at ias-hc-lg me-1"></i>Correo electr&oacute;nico:</label>
							<input type="email" class="form-control" id="email" name="email" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-6">
							<label for="password" class="form-label"><i class="ias ias-person-lock ias-hc-lg me-1"></i>Contrase&ntilde;a:</label>
							<input type="password" class="form-control" id="password" name="password" minlength="6" maxlength="60" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-6">
							<label for="passwordVerify" class="form-label"><i class="ias ias-unlock ias-hc-lg me-1"></i>Confirme contrase&ntilde;a:</label>
							<input type="password" class="form-control" id="passwordVerify" name="passwordVerify" minlength="6" maxlength="60" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-12">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="eula" id="eula" required="true">
								<label class="form-check-label" for="eula">Estoy de acuerdo con los t&eacute;rminos y condiciones.</label>
							</div>
						</div>
	
						<div class="col-12 d-flex justify-content-end">
							<button class="btn btn-success" type="submit">Enviar</button>
						</div>
					
						<hr class="my-3">
					
						<h2 class="fs-5 fw-bold mb-3"><i class="ias ias-ui-radios me-1"></i>Otra opci&oacute;n</h2>
						<div class="dropdown-menu position-static d-grid bg-transparent p-0 border-0">
							<a class="dropdown-item rounded gap-2 py-2" href="<?php echo SERVER['url']; ?>"><i class="ias ias-door-closed ias-hc-lg me-1"></i>Ya estoy registrado, iniciar sesi&oacute;n</a>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</section>

<?php require 'templates/footer.php'; ?>