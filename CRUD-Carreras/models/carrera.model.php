<?php
/*
    Modelo: carreraModel
    Descripción: Modelo para gestionar los datos de las carreras de montaña
*/

class carreraModel extends Model {

    /*
        Método: get()
        Descripción: Obtiene todas las carreras con el nombre del organizador
    */
    public function get() {
        try {
            $sql = "SELECT 
                        c.id,
                        c.nombre,
                        c.fecha,
                        c.ubicacion,
                        c.distancia,
                        c.desnivel,
                        c.dificultad,
                        u.nombre AS organizador
                    FROM eventos AS c
                    LEFT JOIN usuarios AS u ON c.organizador_id = u.id
                    ORDER BY c.fecha ASC";

            $db = $this->db->connect();
            $stmt = $db->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            
            return $stmt;

        } catch (PDOException $e) {
            $this->handleError($e); 
        }
    }

    /*
        Método: get_organizadores()
        Descripción: Obtiene un array asociativo (id => nombre) de los usuarios
    */
    public function get_organizadores() {
        try {
            $sql = "SELECT id, nombre FROM usuarios ORDER BY nombre ASC";
            $db = $this->db->connect();
            $stmt = $db->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
            $stmt->execute();

            return $stmt->fetchAll();
           
        } catch (PDOException $e) {
            $this->handleError($e); 
        }
    }

    /*
        Método: create($carrera)
        Descripción: Inserta una nueva carrera
    */
    public function create(class_carrera $carrera) {
        try {
            $sql = "INSERT INTO eventos 
                    (nombre, fecha, ubicacion, distancia, desnivel, dificultad, descripcion, imagenUrl, organizador_id)
                    VALUES
                    (:nombre, :fecha, :ubicacion, :distancia, :desnivel, :dificultad, :descripcion, :imagenUrl, :organizador_id)";

            $db = $this->db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':nombre',         $carrera->nombre);
            $stmt->bindParam(':fecha',          $carrera->fecha);
            $stmt->bindParam(':ubicacion',      $carrera->ubicacion);
            $stmt->bindParam(':distancia',      $carrera->distancia);
            $stmt->bindParam(':desnivel',       $carrera->desnivel, PDO::PARAM_INT);
            $stmt->bindParam(':dificultad',     $carrera->dificultad);
            $stmt->bindParam(':descripcion',    $carrera->descripcion);
            $stmt->bindParam(':imagenUrl',      $carrera->imagenUrl);
            $stmt->bindParam(':organizador_id', $carrera->organizador_id, PDO::PARAM_INT);

            $stmt->execute();

            return $db->lastInsertId();

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: read($id)
        Descripción: Obtiene un objeto class_carrera por ID
    */
    public function read(int $id) {
        try {
            $sql = "SELECT * FROM eventos WHERE id = :id LIMIT 1";
            
            $db = $this->db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            $this->handleError($e); 
        }
    }

    /*
        Método: update($carrera, $id)
        Descripción: Actualiza los datos de una carrera
    */
    public function update(class_carrera $carrera, $id) {
        try {
            $sql = "UPDATE eventos 
                    SET 
                        nombre = :nombre,
                        fecha = :fecha, 
                        ubicacion = :ubicacion, 
                        distancia = :distancia, 
                        desnivel = :desnivel,
                        dificultad = :dificultad, 
                        descripcion = :descripcion,
                        imagenUrl = :imagenUrl,
                        organizador_id = :organizador_id
                    WHERE id = :id 
                    LIMIT 1";

            $db = $this->db->connect();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':nombre',         $carrera->nombre);
            $stmt->bindParam(':fecha',          $carrera->fecha);
            $stmt->bindParam(':ubicacion',      $carrera->ubicacion);
            $stmt->bindParam(':distancia',      $carrera->distancia);
            $stmt->bindParam(':desnivel',       $carrera->desnivel, PDO::PARAM_INT);
            $stmt->bindParam(':dificultad',     $carrera->dificultad);
            $stmt->bindParam(':descripcion',    $carrera->descripcion);
            $stmt->bindParam(':imagenUrl',      $carrera->imagenUrl);
            $stmt->bindParam(':organizador_id', $carrera->organizador_id, PDO::PARAM_INT);
            $stmt->bindParam(':id',             $id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    /*
        Método: delete($id)
    */
    public function delete(int $id) {
        try {
            $sql = "DELETE FROM eventos WHERE id = :id LIMIT 1";
            $db = $this->db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            $this->handleError($e); 
        }
    }

    /*
        Validación: Verifica que el organizador (usuario) exista
    */
    public function validate_organizador_exists($organizador_id) {
        try {
            $sql = "SELECT id FROM usuarios WHERE id = :id";
            $db = $this->db->connect();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $organizador_id, PDO::PARAM_INT);
            $stmt->execute();

            return ($stmt->rowCount() > 0); 

        } catch (PDOException $e) {
            $this->handleError($e); 
        }
    }

    /*
        Método: handleError
        Descripción: Maneja los errores de la base de datos
    */
    private function handleError(PDOException $e) {
        $errorControllerFile = CONTROLLER_PATH . ERROR_CONTROLLER . '.php';
        
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            $mensaje = $e->getMessage() . " en la línea " . $e->getLine() . " del archivo " . $e->getFile();
            $controller = new Errores('DE BASE DE DATOS', 'Error en CarreraModel: ', $mensaje);
            exit();
        } else {
            echo "Error crítico: " . $e->getMessage();
            exit();
        }
    }
}
?>