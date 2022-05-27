<?php

declare(strict_types=1);

namespace Psr\Log;

/**
 * Represents all possible log levels
 *
 * @author dev
 */
enum LogLevel: int
{
    case EMERGENCY = 1;
    case ALERT     = 2;
    case CRITICAL  = 3;
    case ERROR     = 4;
    case WARNING   = 5;
    case NOTICE    = 6;
    case INFO      = 7;
    case DEBUG     = 8;

    /**
     * Translate an enumeration value to a string label
     *
     * @return string A label for the enum value
     */
    public function label(): string
    {
        return match ($this) {
            static::EMERGENCY => 'emergency',
            static::ALERT => 'alert',
            static::CRITICAL => 'critical',
            static::ERROR => 'error',
            static::WARNING => 'warning',
            static::NOTICE => 'notice',
            static::INFO => 'info',
            static::DEBUG => 'debug'
        };
    }

    /**
     * Checks that the current level is reportable when a base log level
     * is defined
     *
     * @param LogLevel $level The base log level
     * @return bool
     */
    public function inRangeOf(LogLevel $level): bool
    {
        return ($this->value <= $level->value);
    }
}
