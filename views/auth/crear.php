<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ;?>

  
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en TaskNexus</p>
        <?php include_once __DIR__ . '/../templates/alertas.php' ;?>
        <form action="/crear" class="formulario" method='POST'>
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingresa tu nombre" value="<?php echo $usuario->nombre ;?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Ingresa tu Email" value="<?php echo $usuario->email ;?>" >
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" >
            </div>
            <div class="campo">
                <label for="password2">Confirma tu Contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="Confirma tu contraseña" >
            </div>

            <input type="submit" value="Crear Cuenta" class="boton">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu contraseña? Recupérala</a>
        </div>
    </div> 
    <!-- end contenedor-sm -->
</div>
