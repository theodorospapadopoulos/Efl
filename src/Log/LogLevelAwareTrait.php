<?php

declare(strict_types=1);

namespace Efl\Log;

use Psr\Log\LogLevel;

/**
 * A trait that can be used as a fast implementation
 * of LogLevelAwareInterface
 *
 * @author Theodoros Papadopoulos
 */
trait LogLevelAwareTrait
{
    /**
     * The base log level. Below this nothing is logged
     * The default value is the most permissive log level possible
     *
     * @var LogLevel
     */
    private LogLevel $logLevel = LogLevel::DEBUG;

    /**
     * Get the current active logging level
     *
     * @return LogLevel
     */
    public function getLogLevel(): LogLevel
    {
        return $this->logLevel;
    }

    /**
     * Set the current active logging level
     *
     * @param LogLevel $logLevel
     * @return void
     */
    public function setLogLevel(LogLevel $logLevel): void
    {
        $this->logLevel = $logLevel;
    }

    /**
     * Check if the supplied LogLevel argument can be logged
     * based on the current active log level
     *
     * @param LogLevel $logLevel
     * @return bool
     */
    public function loggable(LogLevel $logLevel): bool
    {
        return $logLevel->inRangeOf($this->logLevel);
    }
}
