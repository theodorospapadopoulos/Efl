<?php

declare(strict_types=1);

namespace Tests\Unit\Psr\Log;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Test the LogLevel enum functions
 *
 * @author Theodoros Papadopoulos
 * @group Logging
 */
class LogLevelTest extends TestCase
{
    public function testEightLogLevelsExist(): void
    {
        $this->assertEquals(8, count(LogLevel::cases()));
    }

    /**
     * @small
     * @return void
     */
    public function testLogLevelInclusionRelativeToBaseLevel():void
    {
        $baselevel = LogLevel::ERROR;
        $this->assertTrue(LogLevel::EMERGENCY->inRangeOf($baselevel));
        $this->assertTrue(LogLevel::ALERT->inRangeOf($baselevel));
        $this->assertTrue(LogLevel::CRITICAL->inRangeOf($baselevel));
        $this->assertTrue(LogLevel::ERROR->inRangeOf($baselevel));
        $this->assertFalse(LogLevel::WARNING->inRangeOf($baselevel));
        $this->assertFalse(LogLevel::NOTICE->inRangeOf($baselevel));
        $this->assertFalse(LogLevel::INFO->inRangeOf($baselevel));
        $this->assertFalse(LogLevel::DEBUG->inRangeOf($baselevel));
    }
}
