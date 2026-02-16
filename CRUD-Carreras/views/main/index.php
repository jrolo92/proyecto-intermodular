<!doctype html>
<html lang="es">

<head>
	<?php require_once 'template/layouts/head.layout.php'; ?>
	<title>Traileros</title>
</head>

<body>
	<!-- Menú fijo superior -->
	<?php require_once("template/partials/menu.principal.partial.php") ?>

	<!-- Capa Principal -->
	<div class="container">
		<br><br><br><br>

		<!-- capa de mensajes -->
		<?php require_once("template/partials/mensaje.partial.php") ?>

		<!-- Estilo card de bootstrap -->
		<div class="card">
			<div class="card-header">
				PROYECTO INTERMODULAR
			</div>
			<div class="card-body">
				<?php require_once("template/partials/cabecera.partial.php") ?>
			</div>
			<div class="card-footer">
				Curso 25/26
			</div>
		</div>

	</div>

	<!-- /.container -->

	<?php require_once("template/partials/footer.partial.php") ?>
	<?php require_once("template/layouts/javascript.layout.php") ?>

</body>

</html>