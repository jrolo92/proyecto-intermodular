<?php

class Carrera extends Controller {

    function __construct() {
        parent::__construct();
    }

    /*
        Método: render
        Descripción: Muestra la lista de carreras
    */
    function render() {
        sec_session_start();
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['carrera']['render']);

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if (isset($_SESSION['notify'])){
            $this->view->notify = $_SESSION['notify'];
            unset($_SESSION['notify']);
        }
        
        $this->view->title = "Panel de Carreras - Proyecto Traileros";
        $this->view->carreras = $this->model->get();

        $this->view->render('carrera/main/index');
    }

    /*
        Método: new
        Descripción: Formulario para crear una nueva carrera
    */
    function new() {
        sec_session_start();
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['carrera']['new']);

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Objeto vacío para el formulario (usando tu clase de entidad)
        $this->view->carrera = new class_carrera();

        if (isset($_SESSION['errors'])){
            $this->view->errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
            $this->view->carrera = $_SESSION['carrera'];
            unset($_SESSION['carrera']);
            $this->view->error = "Existen errores en el formulario";
        }

        $this->view->title = "Publicar Nueva Carrera";
        
        // Obtenemos los organizadores (usuarios con ese rol) para el select
        $this->view->organizadores = $this->model->get_organizadores();

        $this->view->render('carrera/new/index');
    }

    /*
        Método: create
        Descripción: Procesa el insert de la carrera
    */
    public function create() {
        sec_session_start();
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['carrera']['create']);

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $this->handleError();
        }

        // Saneamiento de datos
        $nombre = filter_var($_POST['nombre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $fecha = filter_var($_POST['fecha'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $ubicacion = filter_var($_POST['ubicación'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $distancia = filter_var($_POST['distancia'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $desnivel = filter_var($_POST['desnivel'] ?? '', FILTER_SANITIZE_NUMBER_INT);
        $dificultad = filter_var($_POST['dificultad'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $descripcion = filter_var($_POST['descripcion'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $organizador_id = filter_var($_POST['organizador_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        // Objeto Carrera para persistir datos en caso de error
        $carrera = new class_carrera(
            null, $nombre, $fecha, $ubicacion, $distancia, 
            $desnivel, $dificultad, $descripcion, null, $organizador_id
        );

        // Validación
        $error = [];

        if(empty($nombre)) $error['nombre'] = "El nombre de la carrera es obligatorio";
        if(empty($fecha)) $error['fecha'] = "La fecha es obligatoria";
        if($distancia <= 0) $error['distancia'] = "La distancia debe ser un número positivo";
        if(empty($organizador_id)) $error['organizador_id'] = "Debe asignar un organizador";

        if(!empty($error)){
            $_SESSION['errors'] = $error;
            $_SESSION['carrera'] = $carrera;
            header('Location: ' . URL . 'carrera/new');
            exit();
        }

        // Insertar en la base de datos
        $this->model->create($carrera);

        $_SESSION['notify'] = "Carrera publicada correctamente";
        header('Location: ' . URL . 'carrera');
        exit();
    }

    /*
        Método: edit
        Descripción: Carga datos para editar
    */
    public function edit($params) {
        sec_session_start();
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['carrera']['edit']);

        $id = (int) $params[0];

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $this->view->carrera = $this->model->read($id);
        $this->view->id = $id;

        if (isset($_SESSION['errors'])) {
            $this->view->errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
            $this->view->carrera = $_SESSION['carrera'];
            unset($_SESSION['carrera']);
            $this->view->error = "Errores en la edición";
        }

        $this->view->title = "Editar Carrera";
        $this->view->organizadores = $this->model->get_organizadores();

        $this->view->render('carrera/edit/index');
    }

    /*
        Método: update
        Descripción: Actualiza la carrera
    */
    public function update($params) {
        sec_session_start();
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['carrera']['update']);

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('location:' . URL . 'error');
            exit();
        }

        $id = (int) $params[0];

        // Saneamiento y creación de objeto actualizado (similar al create)
        $nombre = filter_var($_POST['nombre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        // ... (resto de campos saneados)

        $carrera_act = new class_carrera($id, $nombre); // Completar constructor

        // Comprobación de cambios y validación (siguiendo tu lógica del libro)
        // ...

        $this->model->update($carrera_act, $id);
        $_SESSION['notify'] = "Carrera actualizada correctamente";
        header('Location: ' . URL . 'carrera');
        exit();
    }

    /*
        Método: delete
        Descripción: Elimina la carrera
    */
    public function delete($params) {
        sec_session_start();
        $this->requireLogin();
        $this->requirePrivilege($GLOBALS['carrera']['delete']);

        $csrf_token = $_POST['csrf_token'] ?? '';
        if(!hash_equals($_SESSION['csrf_token'], $csrf_token)){
            $this->handleError();
        }

        $id = (int) $params[0];
        
        $this->model->delete($id);
        $_SESSION['notify'] = "Carrera eliminada con éxito";
        header('Location: ' . URL . 'carrera');
        exit();
    }

    // Métodos auxiliares de seguridad (Mismos que en tu ejemplo)
    private function requirePrivilege($allowedRoles){
        if (!in_array($_SESSION['role_id'], $allowedRoles)){
            $_SESSION['notify'] = "No tienes permisos para esta acción";
            header('Location: ' . URL . 'auth/login');
            exit();
        }
    }
    
    private function requireLogin(){
        if(!isset($_SESSION['user_id'])) {
            $_SESSION['notify'] = "Inicia sesión primero";
            header('Location: ' . URL . 'auth/login');
            exit();
        }
    }

    private function handleError() {
        // Lógica de error 403 que ya tienes
        echo "Acceso denegado o token inválido";
        exit();
    }
}
?>