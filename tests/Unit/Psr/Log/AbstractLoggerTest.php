<?php

declare(strict_types=1);

namespace Tests\Unit\Psr\Log;

use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Verifies that the Logger's log function
 * is called or not when the logging level changes
 *
 * @author Theodoros Papadopoulos
 * @group Logging
 */
class AbstractLoggerTest extends TestCase
{
    private const SIMPLE_MESSAGE = 'message';

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForDebug(): void
    {
        $this->runMock(LogLevel::DEBUG, LogLevel::INFO, 'debug');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForInfo(): void
    {
        $this->runMock(LogLevel::INFO, LogLevel::NOTICE, 'info');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForNotice(): void
    {
        $this->runMock(LogLevel::NOTICE, LogLevel::WARNING, 'notice');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForWarning(): void
    {
        $this->runMock(LogLevel::WARNING, LogLevel::ERROR, 'warning');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForError(): void
    {
        $this->runMock(LogLevel::ERROR, LogLevel::CRITICAL, 'error');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForCritical(): void
    {
        $this->runMock(LogLevel::CRITICAL, LogLevel::ALERT, 'critical');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForAlert(): void
    {
        $this->runMock(LogLevel::ALERT, LogLevel::EMERGENCY, 'alert');
    }

    /**
     * @small
     * @return void
     */
    public function testLogMethodIsCalledOrNotForEmergency(): void
    {
        $this->runMock(LogLevel::ALERT, LogLevel::EMERGENCY, 'alert');
    }

    /**
     * @small
     * @return void
     */
    private function runMock(LogLevel $testLevel, LogLevel $nextLevel, string $fn): void
    {
        $mock = $this->getMockForAbstractClass(AbstractLogger::class);
        $mock->setLogLevel($testLevel);
        $mock->expects($this->once())
            ->method('log')
            ->with($testLevel, self::SIMPLE_MESSAGE, []);

        call_user_func_array([$mock, $fn], [self::SIMPLE_MESSAGE]);

        if ($nextLevel !== LogLevel::EMERGENCY) {
            $mock->setLogLevel($nextLevel);
            $mock->expects($this->never())
                ->method('log')
                ->with($testLevel, self::SIMPLE_MESSAGE, []);

            call_user_func_array([$mock, $fn], [self::SIMPLE_MESSAGE]);
        }
    }
}
