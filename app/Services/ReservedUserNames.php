<?php

namespace App\Services;

use Str;

trait ReservedUserNames
{
    public static function rules(): array
    {
        $names = [
            'Admin',
            'Administrator',
            'Operator',
            'Support',
            'Tech',
            'Techsupport',
            'Админ',
            'Администратор',
            'Оператор',
            'Поддержка',
        ];

        $namesLowercase = [];

        foreach ($names as $name) {
            $namesLowercase[] = Str::lower($name);
        }

        return array_merge($names, $namesLowercase);
    }
}
