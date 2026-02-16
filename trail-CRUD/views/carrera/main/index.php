<!doctype html>
<html lang="es">

<head>
    <?php require_once 'template/layouts/head.layout.php'; ?>
    <title><?= $this->title ?> </title>
</head>

<body>
    <?php require_once("template/partials/menu.auth.partial.php") ?>

    <div class="container">
        <br><br><br><br>

        <?php require_once("template/partials/mensaje.partial.php") ?>

        <?php require_once("template/partials/error.partial.php") ?>

        <main>
            <legend>Panel de Carreras - Traileros</legend>

            <?php require_once("views/carrera/partials/menu.carrera.partial.php") ?>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col">Distancia</th>
                            <th scope="col">Desnivel</th>
                            <th scope="col">Dificultad</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Organizador</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($carrera = $this->carreras->fetch()): ?>
                            <tr class="align-middle">
                                <td><?= $carrera['id'] ?></td>
                                <td><strong><?= $carrera['nombre'] ?></strong></td>
                                <td><?= $carrera['ubicacion'] ?></td>
                                <td><?= $carrera['distancia'] ?> km</td>
                                <td><?= $carrera['desnivel'] ?> m+</td>
                                <td>
                                    <span class="badge bg-secondary"><?= $carrera['dificultad'] ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($carrera['fecha'])) ?></td>
                                <td><?= $carrera['organizador'] ?></td>

                                <td>
                                    <div class="btn-group btn-group-toggle">
                                        
                                        <form method="POST" action="<?= URL ?>carrera/delete/<?= $carrera['id'] ?>" style="display:inline;" 
                                              onsubmit="return confirm('¿Realmente deseas eliminar esta carrera?')">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm
                                            <?= !in_array($_SESSION['role_id'], $GLOBALS['carrera']['delete']) ? 'disabled' : null ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                        <a href="<?= URL ?>carrera/edit/<?= $carrera['id'] ?>" class="btn btn-warning btn-sm
                                        <?= !in_array($_SESSION['role_id'], $GLOBALS['carrera']['edit']) ? 'disabled' : null ?>" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="<?= URL ?>carrera/show/<?= $carrera['id'] ?>" class="btn btn-primary btn-sm
                                        <?= !in_array($_SESSION['role_id'], $GLOBALS['carrera']['show']) ? 'disabled' : null ?>" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9">Total carreras: <?= $this->carreras->rowCount() ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br><br><br>
        </main>
    </div>

    <?php require_once("template/partials/footer.partial.php") ?>
    <?php require_once("template/layouts/javascript.layout.php") ?>

</body>
</html>