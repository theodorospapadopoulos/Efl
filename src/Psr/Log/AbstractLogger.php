<?php

declare(strict_types=1);

namespace Psr\Log;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Efl\Log\LogLevelAwareInterface;
use Efl\Log\LogLevelAwareTrait;

/**
 * A base class for easy implementation of the PSR-3 LoggerInterface
 * by extending it and implementing just the log function.
 * It adds a switch for enabling and disabling logging
 * by specifying a base logging level. Although this is not
 * specified in PSR-3, the specification itself is not violated.
 *
 * @author dev
 */
abstract class AbstractLogger implements LoggerInterface, LogLevelAwareInterface
{
    /**
     * Add LogLevelAwareInterface functionality
     * for managing a base logging level
     */
    use LogLevelAwareTrait;

    /**
     * System is unusable.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        // Emergency is always logged under any log level
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::ALERT)) {
            $this->log(LogLevel::ALERT, $message, $context);
        }
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::CRITICAL)) {
            $this->log(LogLevel::CRITICAL, $message, $context);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::ERROR)) {
            $this->log(LogLevel::ERROR, $message, $context);
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::WARNING)) {
            $this->log(LogLevel::WARNING, $message, $context);
        }
    }

    /**
     * Normal but significant events.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::NOTICE)) {
            $this->log(LogLevel::NOTICE, $message, $context);
        }
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::INFO)) {
            $this->log(LogLevel::INFO, $message, $context);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        if ($this->loggable(LogLevel::DEBUG)) {
            $this->log(LogLevel::DEBUG, $message, $context);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param LogLevel $level
     * @param string|Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    abstract public function log(LogLevel $level, string|Stringable $message, array $context = []): void;
}
