<?php

namespace Database;

interface DatabaseInterface
{
    public function getPdo(): \PDO;
}
