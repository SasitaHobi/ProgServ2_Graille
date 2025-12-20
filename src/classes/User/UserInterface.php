<?php

namespace User;

// Définition des infos de base d'un utilisateur
interface UserInterface
{
    public function getId(): ?int;
    public function getUsername(): string;
    public function getEmail(): string;
    public function getRole(): string;
    public function getPassword(): string;

    public function setId(int $id): void;
    public function setUsername(string $username): void;
}
