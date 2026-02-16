<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
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

        <!-- Mostrar tabla de  libros -->
        <!-- contenido principal -->
        <main>
            <legend>Formulario Nuevo Libro</legend>

                <!-- Formulario para crear un nuevo libro -->
                <form action="<?= URL ?>libro/create" method="POST">

                    <!-- Protección CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <!-- Título -->
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control"
                        <?=  (isset($this->errors['titulo'])) ? 'is-invalid': null ?>"
                        name="titulo"
                        value="<?= htmlspecialchars($this->libro->titulo)?>"
                        >
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                                <?= $this->errors['titulo'] ??= null ?>
                        </span>
                    </div>

                    <!-- Autor -->
                    <div class="mb-3">
                        <label for="autor_id" class="form-label">Autor:</label>
                        <select class="form-select" name="autor_id" >
                            <option selected disabled>Seleccione Autor</option>
                            <?php foreach ($this->autores as $id => $nombre): ?>
                                <option value="<?= $id ?>"
                                    <?= ($this->libro->autor_id == $id) ? 'selected' : '' ?>
                                >
                                <?= $nombre ?>                   
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['autor_id'] ??= null ?>   
                        </span>
                    </div>

                    <!-- Editorial -->
                    <div class="mb-3">
                        <label for="editorial_id" class="form-label">Editorial:</label>
                        <select class="form-select" name="editorial_id" >
                            <option selected disabled>Seleccione Editorial</option>
                            <?php foreach ($this->editoriales as $id => $nombre): ?>
                                <option value="<?= $id ?>"
                                    <?= ($this->libro->editorial_id == $id) ? 'selected' : '' ?>
                                >
                                    <?= $nombre ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['editorial_id'] ??= null ?>   
                        </span>
                    </div>

                    <!-- Precio -->
                    <div class="mb-3">
                        <label for="precio_venta" class="form-label">Precio:</label>
                        <input type="number" step="0.01" class="form-control
                        <?= isset($this->errors['precio_venta']) ? 'is-invalid' : null ?>" 
                        name="precio_venta" 
                        value="<?= htmlspecialchars($this->libro->precio_venta) ?>" 
                        >
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['precio_venta'] ??= null ?>   
                        </span>
                    </div>

                    <!-- Unidades -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Unidades:</label>
                        <input type="number" class="form-control
                        <?= isset($this->errors['stock']) ? 'is-invalid' : null ?>" 
                        name="stock" 
                        value="<?= htmlspecialchars($this->libro->stock) ?>" 
                        >
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['stock'] ??= null ?>   
                        </span>
                    </div>

                    <!-- Fecha de edición -->
                    <div class="mb-3">
                        <label for="fecha_edicion" class="form-label">Fecha de edición:</label>
                        <input type="date" class="form-control
                        <?= isset($this->errors['fecha_edicion']) ? 'is-invalid' : null ?>" 
                        name="fecha_edicion"
                        value="<?= htmlspecialchars($this->libro->fecha_edicion) ?>" 
                        >
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['fecha_edicion'] ??= null ?>   
                        </span>
                    </div>

                    <!-- ISBN -->
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN:</label>
                        <input type="text" class="form-control
                        <?= isset($this->errors['isbn']) ? 'is-invalid' : null ?>" 
                        name="isbn"
                        value="<?= htmlspecialchars($this->libro->isbn) ?>" 
                        >
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['isbn'] ??= null ?>   
                        </span>
                    </div>

                    <!-- Géneros -->
                    <div class="mb-3">
                        <label class="form-label">Seleccione Géneros:</label>
                        <div class="row">
                            <?php foreach ($this->generos as $id => $tema): ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input
                                            <?= isset($this->errors['generos_id']) ? 'is-invalid' : '' ?>" 
                                            type="checkbox" 
                                            name="generos_id[]" 
                                            value="<?= $id ?>"
                                            <?= in_array($id, $this->libro->generos_id ?? []) ? 'checked' : '' ?>
                                        >
                                        <label class="form-check-label"><?= $tema ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Mostrar posibles errores de validación -->
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errors['generos_id'] ??= null ?>   
                        </span>
                    </div>

                <!-- botones de acción -->
                <a class="btn btn-secondary" href="<?=  URL ?>libro" role="button"
                    onclick="return confirm('Confimar cancelación artículo')">Cancelar</a>
                <button type="reset" class="btn btn-secondary" onclick="return confirm('Confimar reseteo artículo')">Limpiar</button>
                <button type="submit" class="btn btn-primary">Guardar libro</button>
            </form>

            <br><br><br>
        </main>

    </div>

    <!-- /.container -->

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>