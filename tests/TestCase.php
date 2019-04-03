<?php

namespace TrueLayer\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    private $mock_path = "../mocks";

    /**
     * @param $filename
     * @return false|string
     * @throws \Exception
     */
    public function getMockResponse($filename)
    {
        $filepath = __DIR__ . '/' . $this->mock_path . '/' . $filename;

        if (false === file_exists($filepath)) {
            throw new \Exception("Mock file ($filepath) does not exist.");
        }

        return file_get_contents($filepath);
    }
}
