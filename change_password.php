<?php
	define('META', ['location' => 'Cambiar contrase&ntilde;a']);
	require 'templates/header.php'; 

	if(!$active_session){
		header('location: '.SERVER['url']);
		die();
	}

    if(!empty($_POST)){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $passwordActive = $mysqli->real_escape_string($_POST['passwordActive']);
			$password = $mysqli->real_escape_string($_POST['password']);	
			$passwordVerify = $mysqli->real_escape_string($_POST['passwordVerify']);	
		
			if(empty($passwordActive) OR empty($password) OR empty($passwordVerify)){
				$message[] = "Todos los campos son obligatorios.";

			}elseif(!password_verify($passwordActive, $data_user['password'])){
				$message[] = "La contrase&ntilde;a actual es incorrecta.";

			}elseif(!validaPassword($password, $passwordVerify)){
				$message[] = "Las nuevas contrase&ntilde;as no coinciden.";
			}
			

			if(count($message) == 0){
				$hash = hashPassword($password);

				$return = cambiaPassword($hash, $data_user['id']);

				if($return > 0){
					$success = true;
					$message[] = "Actualizado con &eacute;xito.";
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
							<label for="passwordActive" class="form-label"><i class="ias ias-person-lock ias-hc-lg me-1"></i>Contrase&ntilde;a actual:</label>
							<input type="password" class="form-control" id="passwordActive" name="passwordActive" minlength="6" maxlength="60" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
	
						<div class="col-4">
							<label for="password" class="form-label"><i class="ias ias-person-lock ias-hc-lg me-1"></i>Contrase&ntilde;a nueva:</label>
							<input type="password" class="form-control" id="password" name="password" minlength="6" maxlength="60" required="true">
							<div class="invalid-feedback">Este campo es obligatorio.</div>
						</div>
	
						<div class="col-4">
							<label for="passwordVerify" class="form-label"><i class="ias ias-unlock ias-hc-lg me-1"></i>Confirme contrase&ntilde;a nueva:</label>
							<input type="password" class="form-control" id="passwordVerify" name="passwordVerify" minlength="6" maxlength="60" required="true">
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