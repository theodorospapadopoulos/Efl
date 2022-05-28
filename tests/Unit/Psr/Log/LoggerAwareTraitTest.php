<?php

declare(strict_types=1);

namespace Tests\Unit\Psr\Log;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\LoggerAwareObject;
use Psr\Log\LogLevel;
use Psr\Log\AbstractLogger;

/**
 * Test the LoggerAwareTrait
 *
 * @author Theodoros Papadopoulos
 * @group Logging
 */
class LoggerAwareTraitTest extends TestCase
{
    private const SIMPLE_MESSAGE = 'message';

    /**
     * @small
     * @return void
     */
    public function testSettingLoggerHasEffects(): void
    {
        $obj = new LoggerAwareObject();

        $mock = $this->getMockForAbstractClass(AbstractLogger::class);
        $mock->expects($this->once())
            ->method('log')
            ->with(LogLevel::INFO, self::SIMPLE_MESSAGE, []);

        $obj->setLogger($mock);
        $obj->log(self::SIMPLE_MESSAGE);

        $mock->expects($this->never())
            ->method('log')
            ->with(LogLevel::INFO, self::SIMPLE_MESSAGE, []);

        $obj->setLogger(null);
        $obj->log(self::SIMPLE_MESSAGE);
    }
}
