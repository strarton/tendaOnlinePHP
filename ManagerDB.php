<?php
class ManagerDB {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    // Constructor to initialize the connection
    public function __construct($host, $dbname, $username, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;

        // Create a PDO connection
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            // Set PDO to throw exceptions in case of errors
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connection successful!<br>";
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Method to return the PDO instance for queries
    public function getPDO() {
        return $this->pdo;
    }

    // Expose PDO's prepare method
    public function prepare($query) {
        return $this->pdo->prepare($query);
    }

    // Method to check if a user is admin
    public function isAdmin($userId) {
        $query = "
            SELECT COUNT(*) AS is_admin
            FROM rel_tipus_usuaris
            JOIN tipus_usuaris ON rel_tipus_usuaris.id_tipus = tipus_usuaris.id
            WHERE rel_tipus_usuaris.id_usuari = :userId AND tipus_usuaris.nom_tipus = 'empleat'
        ";

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If the count is greater than 0, the user is an admin
            return $result['is_admin'] > 0;
        } catch (PDOException $e) {
            echo "Error checking admin status: " . $e->getMessage();
            return false;
        }
    }
	public function checkCredentials($email, $password) {
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM usuaris WHERE mail = :email AND contrasenya = :password");
        $stmt->execute([
            ':email' => $email,
            ':password' => $password
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el usuario si existe
    } catch (PDOException $e) {
        throw new Exception("Error checking credentials: " . $e->getMessage());
    }
}

}
