 <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container clearfix">
<?php if(isUserLoggedIn()) { ?>
        <a class="brand" id="logo" href="./" target="_top">Bienvenido <?= $loggedInUser->name?></a>
<?php }else{?>
		<a class="brand" id="logo" href="./" target="_top">Admin Portal 3.0 </a>
<?php }?>
        <ul class="nav pull-right">
<?php if(isUserLoggedIn()) { ?>
            	<li><a href="./" target="_top">Inicio</a></li>
       			<li><a href="change-password.php" target="_top">Cambiar contrase&ntilde;a</a></li>
                <li><a href="update-email-address.php" target="_top">Actualizar email</a></li>
 				<li><a href="logout.php" target="_top">Cerrar Sesion</a></li>
<?php } else { ?>
                <li><a href="index.php" target="_top">Inicio</a></li>
                <li><a href="login.php" target="_top">Iniciar Sesion</a></li>
                <li><a href="register.php" target="_top">Registro</a></li>
<?php } ?>
        </ul>
        
      </div>
    </div>
  </div>