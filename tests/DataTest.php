<?php

namespace TrueLayer\Tests;

use TrueLayer\Data;

class DataTest extends TestCase
{
    public function testWeCanDotAnArray()
    {
        $data = new Data();
        $array = [
            'testing' => 1,
            'test' => [
                'testing' => 1
            ]
        ];

        $dotted_array = [
            'testing' => 1,
            'test.testing' => 1
        ];

        $dot = $data->dot($array);
        $this->assertSame($dotted_array, $dot);
    }
}
