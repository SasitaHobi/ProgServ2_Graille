<?php
namespace Database;

class Database implements DatabaseInterface
{
    const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../config/database.ini';

    private $pdo;

    public function __construct()
    {
        $config = parse_ini_file(self::DATABASE_CONFIGURATION_FILE, true);

        if (!$config) {
            throw new \Exception("Erreur lors de la lecture du fichier de configuration : " . self::DATABASE_CONFIGURATION_FILE);
        }

        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        $this->pdo = new \PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);

        // Création de la base de données si elle n'existe pas
        $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Sélection de la base de données
        $sql = "USE `$database`;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(40) NOT NULL,
            email VARCHAR(40) NOT NULL,
            password VARCHAR(500) NOT NULL,
            role VARCHAR(10) NOT NULL
        );";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        
        // Création de la table `food` si elle n'existe pas
        $sql = "CREATE TABLE IF NOT EXISTS food (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            FOREIGN KEY (user_id) REFERENCES users(id),
            name VARCHAR(40) NOT NULL,
            peremption DATE NOT NULL,
            shop VARCHAR(20),
            qty FLOAT NOT NULL,
            unit VARCHAR(10) NOT NULL,
            spot VARCHAR(20) NOT NULL
        );";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
