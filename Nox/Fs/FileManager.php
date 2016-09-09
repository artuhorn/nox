<?php

namespace Nox\Fs;

class FileManager
{
    public static function getFiles(string $dir, string $mask, int $option = null)
    {
        $result = glob($dir . $mask, GLOB_BRACE);
        if ($result === false) {
            return [];
        }

        if ($option) {
            foreach ($result as &$path) {
                $path = pathinfo($path, $option);
            }
        }
        return $result;
    }
}
