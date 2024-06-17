<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ;?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar Contraseña</p>
        <?php include_once __DIR__ . '/../templates/alertas.php' ;?>

        <form action="/olvide" class="formulario" method='POST' novalidate >
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Ingresa tu Email" >
            </div>
            
            <input type="submit" value="Recuperar" class="boton">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Crear una</a>
        </div>
    </div> 
    <!-- end contenedor-sm -->
</div>