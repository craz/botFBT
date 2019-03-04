<?php

declare(strict_types=1);

namespace craz\botFBT;

use PHPUnit\Framework\TestCase;

class botFBTTest extends TestCase
{
    /**
     * @var botFBT
     */
    protected $botFBT;

    protected function setUp() : void
    {
        $this->botFBT = new botFBT;
    }

    public function testIsInstanceOfbotFBT() : void
    {
        $actual = $this->botFBT;
        $this->assertInstanceOf(botFBT::class, $actual);
    }
}
