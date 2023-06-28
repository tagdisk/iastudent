<?php
	define('META', ['location' => 'Perfil de usuario']);
	require 'templates/header.php'; 

	if(!$active_session){
		header('location: '.SERVER['url']);
		die();
	}
?>

<section class="container mt-5">
	<div class="p-4 bg-body-tertiary rounded-3">
		<div class="container-fluid py-5">
			<h1 class="display-5 fw-bold">Bienvenido a <?php echo SERVER['title']; ?></h1>
			<p class="col-md-8 fs-4">Te damos la bienvenida <?php echo $data_user['name']; ?>, este es tu perfil de usuario.</p>
		</div>
	</div>
</section>

<?php require 'templates/footer.php'; ?>