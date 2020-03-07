<?php

declare(strict_types=1);

namespace App;

use MyCLabs\Enum\Enum;

/**
 * ユーザタイプEnum
 * @method static UserType ADMIN()
 * @method static UserType GENERAL()
 */
class UserType extends Enum
{
    private const ADMIN = 'admin';
    private const GENERAL = 'general';
}
