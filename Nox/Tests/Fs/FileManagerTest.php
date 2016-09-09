<?php

namespace Nox\Tests\Fs;

use Nox\Fs\FileManager;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../autoload.php';

class FileManagerTest extends TestCase
{
    public function testGetFiles()
    {
        $path = __DIR__ . '/';
        $templates = FileManager::getFiles($path, '*Test'.'*'.'{.php}', PATHINFO_BASENAME);

        $this->assertArraySubset(['FileManagerTest.php'], $templates);
    }
}
