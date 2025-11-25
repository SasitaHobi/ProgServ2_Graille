<?php

namespace User;

class User implements UserInterface
{
    // Propriétés privées pour assurer l'encapsulation

    public const ROLE = [
        'user',
        'admin',
    ];

    private ?int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $role;

    // Constructeur pour initialiser l'objet
    public function __construct(?int $id, string $username, string $email, string $password, string $role)
    {

        // Vérification des données
        if (strlen($username) < 1) {
            throw new \InvalidArgumentException("Le nom doit contenir au moins 1 caractère.");
        }

        if (strlen($username) < 8) {
            throw new \InvalidArgumentException("Le mot de passe doit contenir au moins 8 caractères.");
        }


        // Initialisation des propriétés
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Getters pour accéder aux propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // Setters pour modifier les propriétés
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
