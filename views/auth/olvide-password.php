<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu Password</p>

<?php 

    include_once __DIR__ . '/../templates/alertas.php';

?>


<form action="/olvide" class="formulario" method="POST">

    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="email"
        id="email"
        placeholder="Tu email"
        name="email">
    </div>

        

    <input type="submit" class="boton" value="Reestablecer Cuenta">

</form>

<div class="acciones">
    <a href="/crear-cuenta">Registrate ahora!</a>
    <a href="/">Inicia sesion!</a>

</div>