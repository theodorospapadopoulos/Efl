<?php

declare(strict_types=1);

namespace Tests\Unit\Log;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Efl\Log\LogLevelAwareTrait;

/**
 * Test the LogLevelAwareTrait
 *
 * @author Theodoros Papadopoulos
 * @group Logging
 */
class LogLevelAwareTraitTest extends TestCase
{
    use LogLevelAwareTrait;

    /**
     * @small
     * @return void
     */
    public function testInitialization(): void
    {
        $this->assertSame(LogLevel::DEBUG, $this->logLevel);
    }

    /**
     * @small
     * @return void
     */
    public function testGetSetValidityAndLoggability(): void
    {
        $this->setLogLevel(LogLevel::NOTICE);
        $this->assertSame(LogLevel::NOTICE, $this->getLogLevel());

        $this->assertTrue($this->loggable(LogLevel::EMERGENCY));
        $this->assertTrue($this->loggable(LogLevel::ALERT));
        $this->assertTrue($this->loggable(LogLevel::CRITICAL));
        $this->assertTrue($this->loggable(LogLevel::ERROR));
        $this->assertTrue($this->loggable(LogLevel::WARNING));
        $this->assertTrue($this->loggable(LogLevel::NOTICE));
        $this->assertFalse($this->loggable(LogLevel::INFO));
        $this->assertFalse($this->loggable(LogLevel::DEBUG));
    }
}
