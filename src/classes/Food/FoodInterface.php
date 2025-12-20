<?php

namespace Food;

use DateTime;

// Interface définissant les infos et méthodes de base d'un aliment
interface FoodInterface
{
    public function getId(): ?int;
    public function getName(): string;
    public function getPeremption(): DateTime;
    public function getShop(): ?string;
    public function getQty(): float;
    public function getUnit(): string;
    public function getSpot(): string;

    public function setId(int $id): void;
    public function setName(string $name): void;
    public function setPeremption(DateTime $peremption): void;
    public function setShop(string $shop): void;
    public function setQty(float $qty): void;
    public function setUnit(string $unit): void;
    public function setSpot(string $spot): void;
}
