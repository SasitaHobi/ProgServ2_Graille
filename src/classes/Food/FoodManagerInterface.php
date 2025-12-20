<?php

namespace Food;

// Définition des actions de base pour la gestion des aliments
interface FoodManagerInterface
{
    public function getFood(): array;
    public function addFood(Food $food): int;
    public function removeFood(int $id): bool;
    public function updateFood(int $id, array $data): bool;
}
