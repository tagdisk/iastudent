<?php 
	define('META', ['location' => 'Inicio de sesi&oacute;n']);
	require 'templates/header.php';

	if($active_session){
		header('location: '.SERVER['url'].'welcome.php');
		die();
	}

	if(!empty($_POST)){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){

			$userKey = $mysqli->real_escape_string($_POST['userKey']);
			$password = $mysqli->real_escape_string($_POST['password']);
			$session = isset($_POST['session']) ? $mysqli->real_escape_string($_POST['session']) : null;


			$strSession = match($session){
				'on' => '1',
				default => '0'
			};
			
			if(isNullLogin($userKey, $password)){
				$message[] = "Todos los campos son obligatorios.";
			}
			
			if(count($message) == 0){
				$message[] = login($userKey, $password, $strSession);
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
					<div class="form-floating mb-3">
						<input type="text" class="form-control rounded-3" id="userKey" placeholder="" name="userKey" minlength="3" required="true">
						<label for="userKey">Usuario | Correo electr&oacute;nico</label>
						<div class="invalid-feedback">Por favor, no escribas menos de 4 caracteres.</div>
					</div>
					<div class="form-floating mb-3">
						<input type="password" class="form-control rounded-3" id="password" placeholder="" name="password" minlength="6" required="true">
						<label for="password">Contrase&ntilde;a</label>
						<div class="invalid-feedback">Por favor, no escribas menos de 6 caracteres.</div>
					</div>
					
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="session" id="session">
						<label class="form-check-label" for="session">Mantener sesi&oacute;n en este navegador.</label>
					</div>
				
					<button id="loginSubmit" class="w-100 mb-2 btn btn-primary btn-lg text-uppercase rounded-3" type="submit">Ingresar<i class="ias ias-door-closed ias-hc-lg ms-1"></i></button>
				
					<hr class="my-3">
				
					<h2 class="fs-5 fw-bold mb-3"><i class="ias ias-ui-radios me-1"></i>Otras opciones</h2>
					<div class="dropdown-menu position-static d-grid bg-transparent p-0 border-0">
						<a class="dropdown-item rounded gap-2 py-2" href="<?php echo SERVER['url'].'signin.php'; ?>"><i class="ias ias-person-add ias-hc-lg me-1"></i>¿Eres nuevo? Reg&iacute;strate</a>
				
						<a class="dropdown-item rounded gap-2 py-2" href="<?php echo SERVER['url'].'recovery_password.php'; ?>"><i class="ias ias-shield-lock ias-hc-lg me-1"></i>¿He olvidado mi contrase&ntilde;a?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<?php require 'templates/footer.php'; ?>