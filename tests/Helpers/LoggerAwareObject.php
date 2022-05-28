<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Helper for testing the LoggerAwareTrait
 *
 * @author Theodoros Papadopoulos
 */
class LoggerAwareObject implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * 
     * @param string|Stringable $message
     * @return void
     */
    public function log(string|Stringable $message): void
    {
        $this->logger?->info($message);
    }
}
