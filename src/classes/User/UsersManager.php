<?php

namespace User;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database\Database;

class UsersManager implements UsersManagerInterface
{
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function getUser(): array
    {
        // Définition de la requête SQL pour récupérer tous les utilisateurs
        $sql = "SELECT * FROM users";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de tous les utilisateurs
        $user = $stmt->fetchAll();

        // Transformation des tableaux associatifs en objets user
        $user = array_map(function ($userData) {
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['password'],
                $userData['role'],
            );
        }, $user);

        // Retour de tous les utilisateurs
        return $user;
    }

    public function getUserById(int $id): ?user
    {
        // Définition de la requête SQL pour récupérer un utilisateur par ID
        $sql = "SELECT * FROM users WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de l'utilisateur
        $userData = $stmt->fetch();

        // Si l'utilisateur n'existe pas, retourner null
        if (!$userData) {
            return null;
        }

        // Transformation du tableau associatif en objet user
        return new User(
            $userData['id'],
            $userData['username'],
            $userData['password'],
            $userData['role']
        );
    }

    public function addUser(User $user): int
    {
        // Définition de la requête SQL pour ajouter un utilisateur
        $sql = "INSERT INTO User (
            username,
            password,
            role
        ) VALUES (
            :username,
            :password,
            :role
        )";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role', $user->getRole());

        // Exécution de la requête SQL pour ajouter un utilisateur
        $stmt->execute();

        // Récupération de l'identifiant de l'utilisateur ajouté
        $userId = $this->database->getPdo()->lastInsertId();

        // Retour de l'identifiant de l'utilisateur ajouté.
        return $userId;
    }

    public function removeUser(int $id): bool
    {
        // Définition de la requête SQL pour supprimer un utilisateur
        $sql = "DELETE FROM users WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL pour supprimer un utilisateur
        return $stmt->execute();
    }
}
