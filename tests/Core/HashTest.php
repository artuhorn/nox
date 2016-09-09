<?php

namespace Nox\Helpers;

use Nox\Core\Hash;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{
    public function testCanAccessViaArraySyntax()
    {
        $base = new Hash();
        $base['testProperty']['value'] = 20;

        $this->assertArrayHasKey('testProperty', $base);
        $this->assertEquals($base['testProperty']['value'], 20);
    }

    public function testCanAccessViaHashSyntax()
    {
        $base = new Hash();
        $base->testProperty->value = 20;

        $this->assertEquals(20, $base->testProperty->value);
    }

    public function testEmptyPropertyIsObject()
    {
        $base = new Hash();
        $base->testProperty->deeper = true;

        $this->assertEquals(true, is_a($base->testProperty, Hash::class));
    }
}
