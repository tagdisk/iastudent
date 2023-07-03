<?php
	require 'drivers/connection.php';
    require 'drivers/functions.php';
?>
<!DOCTYPE html>
<html lang="es" class="h-100 user-select-none d-print-none">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de control de asistencia virtual basado en inteligencia artificial para estudiante en la Universidad Polit&eacute;cnica Territorial del Estado Aragua “Dr. Federico Brito Figueroa”.">
    <meta name="author" content="Jos&eacute; Hern&aacute;ndez, Gaby Hernandez y Enmanuel Zamora">
    <meta name="generator" content="<?php echo SERVER['title'].' 0.53.1'; ?>">
    <title><?php echo SERVER['title'].' · '.META['location']; ?></title>
    <link href="<?php echo SERVER['url'].'assets/img/favicons/setup_icon.ico'; ?>" rel="icon">
    <link href="<?php echo SERVER['url'].'assets/css/bootstrap.min.css'; ?>" rel="stylesheet">
    <link href="<?php echo SERVER['url'].'assets/css/iastudent.css'; ?>" rel="stylesheet">
    <link href="<?php echo SERVER['url'].'assets/css/iastudent-icons.css'; ?>" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
<?php if($active_session){ ?> 
<header>
    <nav class="navbar navbar-expand-sm fixed-top bg-body-tertiary bg-opacity-75 disabled">
        <div class="container-fluid">
            <a class="text-decoration-none me-0" href="<?php echo SERVER['url'].'welcome.php'; ?>">
                <figure class="rounded-0 d-inline-block position-relative m-0">
                    <img class="rounded-0 p-1" src="<?php echo SERVER['url'].'assets/img/favicons/setup_small.webp'; ?>" alt="..." name="<?php echo SERVER['title']; ?>" data-width="50" data-height="50" width="50">
                </figure>
                <span class="h4 align-middle text-body-emphasis fw-bold"><?php echo SERVER['title']; ?></span>
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto ms-5">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo SERVER['url'].'welcome.php'; ?>"><i class="ias ias-house-check ias-hc-lg me-1"></i>Inicio</a></li>

                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#aboutModal"><i class="ias ias-patch-exclamation ias-hc-lg me-1"></i>Acerca de..</a></li>
                </ul>

                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-decoration-none lead-sm fw-normal text-body-emphasis" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" class="text-decoration-none border-0 position-relative me-3">
                        <img class="rounded-pill p-1" src="<?php echo SERVER['url'].'assets/img/avatars/'.$data_user['gender'].'_avatar.webp'; ?>" alt="..." width="48" height="48">
                        <?php echo $data_user['name'].' '.$data_user['surname']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-macos dropdown-menu-end" style="width: 400px;">

                        <li class="list-group-item d-flex gap-2">
                            <img src="<?php echo SERVER['url'].'assets/img/avatars/'.$data_user['gender'].'_avatar.webp'; ?>" alt="..." width="128" height="128" class="rounded-circle flex-shrink-0">
                            <div class="d-flex gap-2 w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?php echo $data_user['name'].' '.$data_user['surname']; ?></h6>
                                    <p class="mb-0 opacity-75"><?php echo $data_user['email']; ?></p>
                                    <p class="mb-0 opacity-75">@<?php echo $data_user['user']; ?></p>
                                    <?php 
                                        if($data_user['type_user'] == 'admin'){
                                            echo '<span class="badge text-bg-danger rounded-pill">Usuario administrador</span>';
                                        }else{
                                            echo '<span class="badge text-bg-success rounded-pill">Usuario regular</span>';
                                        }
                                    ?>
                                </div>
                            
                            </div>
                        </li>    
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item py-2" href="<?php echo SERVER['url'].'edit_user.php'; ?>"><i class="ias ias-gear ias-hc-lg me-1"></i>Editar perfil</a></li>

                        <li><a class="dropdown-item py-2" href="<?php echo SERVER['url'].'change_password.php'; ?>"><i class="ias ias-shield-lock ias-hc-lg me-1"></i>Cambiar contrase&ntilde;a</a></li>

                        <li><a class="dropdown-item py-2" href="<?php echo SERVER['url'].'logout.php'; ?>"><i class="ias ias-door-open ias-hc-lg me-1"></i>Cerrar sesi&oacute;n</a></li>

                    </ul>
                </div>
  
            </div>

        </div>
    </nav>
</header>

<div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header p-4 pb-3 border-bottom-0">
                <h1 class="fs-3 fw-semibold text-body-emphasis" id="aboutModalLabel"><?php echo 'Acerca de '.SERVER['title']; ?></h1>
                <button type="button" class="btn-close p-3 bg-secondary bg-opacity-10 rounded-pill" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4 pt-1">
                <p class="lead">Este proyecto es desarrollado por los siguientes autores:</p>

                <div class="d-flex flex-row align-items-center mb-3">
                    <figure class="figure position-relative">
                        <img class="figure-img img-fluid rounded-circle mx-auto" src="<?php echo SERVER['url'].'assets/img/avatars/man_avatar.webp'; ?>" alt="..." height="80" width="80">
                    </figure>
                    <div class="p-2 fs-5 fw-normal">Jos&eacute; Hern&aacute;ndez<p class="lead-sm">24 a&ntilde;os <span class="badge rounded-pill text-bg-success me-1">Programador</span><span class="badge rounded-pill text-bg-success">Estudiante</span></p></div>
                    
                </div>

                <div class="d-flex flex-row align-items-center mb-3">
                    <figure class="figure position-relative">
                        <img class="figure-img img-fluid rounded-circle mx-auto" src="<?php echo SERVER['url'].'assets/img/avatars/woman_avatar.webp'; ?>" alt="..." height="80" width="80">
                    </figure>
                    <div class="p-2 fs-5 fw-normal">Gaby Hernandez<p class="lead-sm">20 a&ntilde;os <span class="badge rounded-pill text-bg-success me-1">Programadora</span><span class="badge rounded-pill text-bg-success">Estudiante</span>
                    </p></div>
                </div>

                <div class="d-flex flex-row align-items-center mb-3">
                    <figure class="figure position-relative">
                        <img class="figure-img img-fluid rounded-circle mx-auto" src="<?php echo SERVER['url'].'assets/img/avatars/man_avatar.webp'; ?>" alt="..." height="80" width="80">
                    </figure>
                    <div class="p-2 fs-5 fw-normal">Enmanuel Zamora<p class="lead-sm">24 a&ntilde;os <span class="badge rounded-pill text-bg-success me-1">Programador</span><span class="badge rounded-pill text-bg-success">Estudiante</span></p></div>
                </div>
                  
            </div>
            
        </div>
    </div>
</div>
<?php } ?> 
<main class="container-fluid flex-shrink-0 mb-5">