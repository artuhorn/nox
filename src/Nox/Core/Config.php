<?php

namespace Nox\Core;


use Nox\Helpers\TSingleton;

/**
 * Class Config
 * @package Nox\Core
 * @property string $templatesPath
 *
 * @property string $cachePath
 *
 */
class Config extends Hash
{
    use TSingleton;
    
    public function loadFromJson(string $filename)
    {
        $json = file_get_contents(realpath($filename));
        $this->fromArray(json_decode($json, true));
    }
    
    public function loadFromArray(array $data)
    {
        $this->fromArray($data);
    }
}