<?php

namespace Food;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database\Database;

class FoodManager implements FoodManagerInterface
{
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function getFood(): array
    {
        // Définition de la requête SQL pour récupérer tous les aliments
        $sql = "SELECT * FROM food";

        // requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute();
        $food = $stmt->fetchAll();

        // Transformation des tableaux associatifs en objets Food
        $food = array_map(function ($foodData) {
            return new Food(
                $foodData['id'],
                $foodData['user_id'],
                $foodData['name'],
                new \DateTime($foodData['peremption']),
                $foodData['shop'],
                $foodData['qty'],
                $foodData['unit'],
                $foodData['spot'],
            );
        }, $food);

        // Retour de tous les aliments
        return $food;
    }

    public function getFoodById(int $id): ?Food
    {
        // Définition de la requête SQL pour récupérer un aliment par ID
        $sql = "SELECT * FROM food WHERE id = :id";

        // requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Récupération de l'aliment
        $foodData = $stmt->fetch();

        // Si l'aliment n'existe pas, retourner null
        if (!$foodData) {
            return null;
        }

        // Transformation du tableau associatif en objet Food
        return new Food(
            $foodData['id'],
            $foodData['user_id'],
            $foodData['name'],
            new \DateTime($foodData['peremption']),
            $foodData['shop'],
            $foodData['qty'],
            $foodData['unit'],
            $foodData['spot']
        );
    }

    public function addFood(Food $food): int
    {
        // Définition de la requête SQL pour ajouter un aliment
        $sql = "INSERT INTO food (
            name,
            peremption,
            shop,
            qty,
            unit,
            spot
        ) VALUES (
            :name,
            :peremption,
            :shop,
            :qty,
            :unit,
            :spot
        )";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':name', $food->getName());
        $stmt->bindValue(':peremption', $food->getPeremption());
        $stmt->bindValue(':shop', $food->getShop());
        $stmt->bindValue(':qty', $food->getQty());
        $stmt->bindValue(':unit', $food->getUnit());
        $stmt->bindValue(':spot', $food->getSpot());


        // Exécution de la requête SQL pour ajouter un aliment
        $stmt->execute();

        // Récupération de l'identifiant de l'aliment ajouté
        $foodId = $this->database->getPdo()->lastInsertId();

        // Retour de l'identifiant de l'aliment ajouté.
        return $foodId;
    }

    public function removeFood(int $id): bool
    {
        // Définition de la requête SQL pour supprimer un aliment
        $sql = "DELETE FROM food WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL pour supprimer un aliment
        return $stmt->execute();
    }

    public function updateFood(int $id, array $data): bool
    {
        $sql = "UPDATE food SET name = :name, shop = :shop, qty = :qty, unit = :unit, spot = :spot, peremption = :peremption WHERE id = :id";
        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':shop', $data['shop']);
        $stmt->bindValue(':qty', $data['qty']);
        $stmt->bindValue(':unit', $data['unit']);
        $stmt->bindValue(':spot', $data['spot']);
        $stmt->bindValue(':peremption', $data['peremption']);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        // Retourne true si au moins 1 ligne a été modifiée
        return $stmt->rowCount() > 0;
    }
}
