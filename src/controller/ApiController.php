<?php

class ApiController {

    const OBJECT = 1;
    const JSON = 2;

    private $connection;

    public function __construct() {
        $this->connection = DatabaseController::connect();
    }

    // Devuelve todos los usuarios
    public static function getLinks($mode = self::OBJECT) {
        try {
            $sql = "SELECT * FROM User";
            $statement = (new self)->connection->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute();
            $result = $statement->fetchAll();

            return $mode === self::OBJECT ? $result : json_encode($result, JSON_PRETTY_PRINT);
        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    // Devuelve un usuario por ID
    public static function getUser($id, $mode = self::OBJECT) {
        try {
            $sql = "SELECT * FROM User WHERE id = ?";
            $statement = (new self)->connection->prepare($sql);
            $statement->execute([$id]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            return $mode === self::OBJECT ? $user : json_encode($user, JSON_PRETTY_PRINT);
        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    // Crea un nuevo usuario
    public static function createUser($data) {
        try {
            $sql = "INSERT INTO User (name, surname, email, dni, phone, born) VALUES (?, ?, ?, ?, ?, ?)";
            $statement = (new self)->connection->prepare($sql);
            $statement->execute([$data['name'], $data['surname'], $data['email'], $data['dni'], $data['phone'], $data['born']]);
            return json_encode(['message' => 'Usuario creado correctamente']);
        } catch(PDOException $error) {
            return json_encode(['message' => $error->getMessage()]);
        }
    }

    // Actualiza un usuario por ID
    public static function updateUser($id, $data) {
        try {
            $sql = "UPDATE User SET name = ?, surname = ?, email = ?, dni = ?, phone = ?, born = ? WHERE id = ?";
            $statement = (new self)->connection->prepare($sql);
            $statement->execute([$data['name'], $data['surname'], $data['email'], $data['dni'], $data['phone'], $data['born'], $id]);
            return json_encode(['message' => 'Usuario actualizado correctamente']);
        } catch(PDOException $error) {
            return json_encode(['message' => $error->getMessage()]);
        }
    }

    // Elimina un usuario por ID
    public static function deleteUser($id) {
        try {
            $sql = "DELETE FROM User WHERE id = ?";
            $statement = (new self)->connection->prepare($sql);
            $statement->execute([$id]);
            return json_encode(['message' => 'Usuario eliminado correctamente']);
        } catch(PDOException $error) {
            return json_encode(['message' => $error->getMessage()]);
        }
    }
}
?>

