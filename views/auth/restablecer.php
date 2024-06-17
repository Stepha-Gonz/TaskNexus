<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ;?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu nueva contraseña</p>

        
        <?php include_once __DIR__ . '/../templates/alertas.php' ;?>
        <?php if($mostrar) { ?>
        <form  class="formulario" method='POST'>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña">
            </div>
            <div class="campo">
                <label for="password2">Confirma tu Contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="Confirma tu contraseña">
            </div>
            
            <input type="submit" value="Guardar Contraseña" class="boton">
        </form>

        <?php }?>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Crear una</a>
        </div>
    </div> 
    <!-- end contenedor-sm -->
</div>