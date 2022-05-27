<?php

declare(strict_types=1);

namespace Efl\Log;

use Psr\Log\LogLevel;

/**
 * An interface to manipulate a logger's logging level
 *
 * @author Theodoros Papadopoulos
 */
interface LogLevelAwareInterface
{
    /**
     * Get the current active logging level
     *
     * @return LogLevel
     */
    public function getLogLevel(): LogLevel;

    /**
     * Set the current active logging level
     *
     * @param LogLevel $level
     * @return void
     */
    public function setLogLevel(LogLevel $level): void;

    /**
     * Check if the supplied LogLevel argument can be logged
     * based on the current active log level
     *
     * @param LogLevel $level
     * @return bool
     */
    public function loggable(LogLevel $level): bool;
}
