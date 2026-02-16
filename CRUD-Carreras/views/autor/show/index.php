<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?></title>
</head>

<body>

    <!-- Menú fijo superior -->
    <?php require_once("template/partials/menu.partial.php") ?>

    <!-- Capa Principal -->
    <div class="container">
        <br><br><br><br>

        <!-- capa de mensajes -->
        <?php require_once("template/partials/mensaje.partial.php") ?>

        <!-- capa de errores -->
        <?php require_once("template/partials/error.partial.php") ?>

        <!-- contenido principal -->
        <main>
            <legend><?= $this->title ?></legend>

            <!-- Formulario solo lectura para mostrar autor -->
            <form>

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" value="<?= $this->autor->nombre ?>" disabled>
                </div>

                <!-- Nacionalidad -->
                <div class="mb-3">
                    <label class="form-label">Nacionalidad:</label>
                    <input type="text" class="form-control" value="<?= $this->autor->nacionalidad ?>" disabled>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" value="<?= $this->autor->email ?>" disabled>
                </div>

                <!-- Fecha de nacimiento -->
                <div class="mb-3">
                    <label class="form-label">Fecha de nacimiento:</label>
                    <input type="date" class="form-control" value="<?= $this->autor->fecha_nac ?>" disabled>
                </div>

                <!-- Premios -->
                <div class="mb-3">
                    <label class="form-label">Premios:</label>
                    <textarea class="form-control" rows="3" disabled><?= $this->autor->premios ?></textarea>
                </div>

                <!-- Botón volver -->
                <a class="btn btn-secondary" href="<?= URL ?>autor" role="button">Volver</a>

            </form>

            <br><br><br>
        </main>

    </div>

    <!-- /.container -->

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>
</html>
