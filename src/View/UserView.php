<?php

declare(strict_types=1);

namespace App\View;

class UserView extends View
{
    public ?string $email = null;

    public ?string $firstName = null;

    public ?string $lastName = null;
}