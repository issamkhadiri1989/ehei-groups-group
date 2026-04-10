<?php

namespace App\DTO;

class LoginRequest
{
    public string $username;

    public string $password;

    public object $agency;
}
