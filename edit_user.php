<?php
	define('META', ['location' => 'Editar perfil']);
	require 'templates/header.php'; 

	if(!$active_session){
		header('location: '.SERVER['url']);
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
		
			if(empty($name) OR empty($surname) OR empty($gender) OR empty($user) OR empty($DNIprefix) OR empty($dni) OR empty($email)){
				$message[] = "Todos los campos son obligatorios.";

			}elseif(!isEmail($email)){
				$message[] = "Por favor, escribe una direcci&oacute;n de correo v&aacute;lida.";

			}elseif(usuarioExiste($user) AND $data_user['user'] <> $user){
				$message[] = "El nombre de usuario: $user, ya est&aacute; en uso.";

			}elseif(emailExiste($email) AND $data_user['email'] <> $email){
				$message[] = "El correo electr&oacute;nico: $email, ya est&aacute; en uso.";

			}
			
			if(count($message) == 0){
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
					'id' => $data_user['id'],
					'name' => filterChar($name),
					'surname' => filterChar($surname),
					'gender' => $strGender,
					'user' => filterChar($user),
					'dni_prefix' => $strDNIPrefix,
					'dni' => $dni,
					'email' => $email
				);

				$return = editarUsuario($data);

				if($return > 0){
					$success = true;
					$message[] = 'Actualizado con &eacute;xito. <p class="m-0 mt-3"><a href="'.SERVER['uri'].'" class="btn btn-primary btn-sm">Refrescar datos</a></p>';

					$id = $_SESSION[strtolower(SERVER['title'])]['id_user'];
					$sql = "SELECT * FROM users WHERE id=$id LIMIT 1";
					$result = $mysqli->query($sql);
					$data_user = $result->fetch_assoc();
				}

			}

		}//End POST
	}	
?>

<section class="container mt-4">
	<div class="row justify-content-center">
		<div class="card px-0 shadow col-12">
			<div class="card-header fs-4 fw-semibold text-bg-primary bg-opacity-75"><?php echo META['location']; ?></div>
		
			<div class="card-body py-4">
				<form class="needs-validation" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" role="form" autocomplete="on" novalidate="true">
					<div class="row g-3">

						<div class="col-4">
							<label for="name" class="form-label"><i class="ias ias-person-lines-fill ias-hc-lg me-1"></i>Nombre/s:</label>
	
							<input type="text" class="form-control text-capitalize" id="name" name="name" minlength="3" maxlength="50" required="true" value="<?php echo $data_user['name']; ?>">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-4">
							<label for="surname" class="form-label"><i class="ias ias-person-lines-fill ias-hc-lg me-1"></i>Apellido/s:</label>
							<input type="text" class="form-control text-capitalize" id="surname" name="surname" minlength="3" maxlength="50" required="true" value="<?php echo $data_user['surname']; ?>">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-4">
							<label for="gender" class="form-label"><i class="ias ias-gender-ambiguous ias-hc-lg me-1"></i>Indica tu g&eacute;nero:</label>
							<select class="form-select" id="gender" name="gender" required="true">
								<option value="" disabled>Elige una opci&oacute;n...</option>
								<option value="1" <?php echo ($data_user['gender'] == 'man') ? 'selected' : null; ?>>Hombre</option>
								<option value="2" <?php echo ($data_user['gender'] == 'woman') ? 'selected' : null; ?>>Mujer</option>
							</select>
							<div class="invalid-feedback">Este campo es obligatorio.</div>
	
						</div>
	
						<div class="col-6">
							<label for="user" class="form-label"><i class="ias ias-person-check ias-hc-lg me-1"></i>Nombre de usario:</label>
							<input type="text" class="form-control" id="user" name="user" minlength="3" maxlength="20" required="true" value="<?php echo $data_user['user']; ?>">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						
						<div class="col-6">
							<label for="dni" class="form-label"><i class="ias ias-postcard ias-hc-lg ias-hc-flip-horizontal me-1"></i>C&eacute;dula de identificaci&oacute;n:</label>
							<div class="row gx-1">
								<div class="col-3">
									<select class="form-select rounded-0 rounded-start" name="prefix" required="true">
										<option value="1" <?php echo ($data_user['dni_prefix'] == 'V') ? 'selected' : null; ?>>V</option>
										<option value="2" <?php echo ($data_user['dni_prefix'] == 'E') ? 'selected' : null; ?>>E</option>
									</select>
									<div class="invalid-feedback">Este campo es obligatorio.</div>
								</div>
	
								<div class="col-9">
									<input type="text" id="dni" class="form-control rounded-0 rounded-end" name="dni" minlength="1" maxlength="8" required="true" value="<?php echo $data_user['dni']; ?>">
									<div class="invalid-feedback">Este campo es obligatorio.</div>
								</div>
	
							</div>
						</div>
						
						<div class="col-12">
							<label for="email" class="form-label"><i class="ias ias-envelope-at ias-hc-lg me-1"></i>Correo electr&oacute;nico:</label>
							<input type="email" class="form-control" id="email" name="email" required="true" value="<?php echo $data_user['email']; ?>">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	

						<div class="col-12 d-flex justify-content-end">
							<button class="btn btn-primary" type="submit">Actualizar</button>
						</div>
					

					</div>
					
				</form>
			</div>
		</div>
	</div>
</section>

<?php require 'templates/footer.php'; ?>