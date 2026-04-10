<h1 class="nombre-pagina">Crear nuevo Servicio</h1>
<p class="descripcion-pagina">Crear Nuevos Servicios</p>

<?php 

include_once __DIR__ . '/../templates/barra.php';

?>

<form  method="POST" class="formulario">

<?php 

include_once __DIR__ . '/../templates/alertas.php';
include_once __DIR__ . '/formulario.php';


?>

    <input type="submit" class="boton" value="Actualizar Servicio">

</form>