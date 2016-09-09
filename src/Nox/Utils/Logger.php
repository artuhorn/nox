<?php

namespace Nox\Helpers;

use Nox\Core\Config;

class Log
{
    protected $filename;

    /**
     * Log constructor
     */
    public function __construct()
    {
        $config = Config::instance();
        $this->filename = __DIR__ . '/../protected/' . $config->data['log']['filename'];
    }


    /**
     * Функция записи информации о событии в файл
     * Информация из полей класса разделяется пятью пробелами
     * @param string $message
     */
    public function saveMessage(string $message)
    {
        $file = fopen($this->filename, 'ab');
        if (flock($file, LOCK_EX)) {
            $time = date('d.m.Y H:i:s');
            fwrite($file, $time . "\n" . $message . "\r\n");
            fflush($file);
            flock($file, LOCK_UN);
            fclose($file);
        }
    }
}
