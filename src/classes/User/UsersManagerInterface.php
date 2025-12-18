<?php

namespace User;

interface UsersManagerInterface
{
    public function getUser(): array;
    public function addUser(User $user): int;
    public function removeUser(int $id): bool;
}
