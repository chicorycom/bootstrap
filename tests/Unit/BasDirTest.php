<?php


namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


class BasDirTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_base_dir()
    {
        print(base_path());
        $this->assertTrue(true);
    }
}