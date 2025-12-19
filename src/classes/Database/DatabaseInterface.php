<?php

namespace Database;

// Interface qui oblige à fournir une connexion PDO
interface DatabaseInterface
{
    public function getPdo(): \PDO;
}
