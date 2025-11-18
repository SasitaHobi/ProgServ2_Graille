<?php

namespace Food;

use DateTime;

class Food implements FoodInterface
{
    // Propriétés privées pour assurer l'encapsulation

    public const UNIT = [
        'pack' => 'Paquet',
        'piece' => 'Pièce',
        'l' => 'Litre',
        'g' => 'Gramme',
        'kg' => 'Kilogramme',
    ];

    public const SPOT = [
        'cupboard' => 'Armoire',
        'fridge' => 'Frigo',
        'freezer' => 'Congélateur',
        'cellar' => 'Cave'
    ];


    private ?int $id;
    private int $userId;
    private string $name;
    private DateTime $peremption;
    private ?string $shop;
    private float $qty;
    private string $unit;
    private string $spot;

    // Constructeur pour initialiser l'objet
    public function __construct(?int $id, int $userId, string $name, DateTime $peremption, ?string $shop, float $qty, string $unit, string $spot)
    {

        // Vérification des données
        if (strlen($name) < 2) {
            throw new \InvalidArgumentException("Le nom doit contenir au moins 2 caractères.");
        }


        if (!filter_var($qty, FILTER_VALIDATE_FLOAT)) {
            throw new \InvalidArgumentException("Un prix valide est requis.");
        } else if ($qty < 0) {
            throw new \InvalidArgumentException("Le prix doit être un nombre positif.");
        }


        // Initialisation des propriétés
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->peremption = $peremption;
        $this->shop = $shop;
        $this->qty = $qty;
        $this->unit = $unit;
        $this->spot = $spot;
    }

    // Getters pour accéder aux propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPeremption(): DateTime
    {
        return $this->peremption;
    }

    public function getShop(): ?string
    {
        return $this->shop;
    }

    public function getQty(): float
    {
        return $this->qty;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getSpot(): string
    {
        return $this->spot;
    }

    // Setters pour modifier les propriétés
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPeremption(DateTime $peremption): void
    {
        $this->peremption = $peremption;
    }

    public function setShop(string $shop): void
    {
        $this->shop = $shop;
    }

    public function setQty(float $qty): void
    {
        if ($qty >= 0) {
            $this->qty = $qty;
        }
    }

    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    public function setSpot(string $spot): void
    {
        $this->spot = $spot;
    }
}
