<?php

namespace User;

// Définition actions de base pour gestion des utilisateurs
interface UsersManagerInterface
{
    public function getUser(): array;
    public function addUser(User $user): int;
    public function removeUser(int $id): bool;
}
