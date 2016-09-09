<?php

namespace Nox\Helpers;

class StringHelper
{
    /**
     * Удаляет последний слэш из строки
     *
     * @param string $value
     * @return string
     */
    public static function cutLastSlash(string $value): string
    {
        $slash = substr($value, -1);
        if (($slash == '/' || $slash == '\\') && strlen($value) > 1) {
            return substr($value, 0, -1);
        };

        return $value;
    }

    /**
     * Возвращает базовое имя класса, без namespace
     *
     * @param string $fullClassName
     * @return string
     */
    public static function getBaseClassName(string $fullClassName)
    {
        return join('', array_slice(explode('\\', $fullClassName), -1));
    }
}
