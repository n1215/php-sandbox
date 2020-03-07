<?php

declare(strict_types=1);

namespace App;

/** ユーザタイプキャスト */
class UserTypeCast extends EnumCast
{
    protected function getClass(): string
    {
        return UserType::class;
    }
}
