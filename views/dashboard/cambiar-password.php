<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
    <a href="/perfil" class="enlace">
        Volver al Perfil
    </a>
    <form method='POST' class="formulario" action="/cambiar-password">
        <div class="campo">
            <label for="password-actual">Contraseña Actual</label>
            <input type="password" name="password_actual"  placeholder="Contraseña Actual">
        </div>
        <div class="campo">
            <label for="password-nuevo">Contraseña Nueva</label>
            <input type="password" name="password_nuevo"  placeholder="Contraseña nueva">
        </div>
        <div class="campo">
            <label for="password-confirmar">Confirmar Contraseña</label>
            <input type="password" name="password_confirmado"   placeholder="Confirma Tu contraseña">
        </div>

        <input type="submit"   value="Guardar Contraseña">
    </form>
</div>









<?php include_once __DIR__ . '/footer-dashboard.php'; ?>