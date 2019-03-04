<?php

use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    public function testWeCanDotAnArray()
    {
        $data = new TrueLayer\Data;
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