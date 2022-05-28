<?php

declare(strict_types=1);

namespace Psr\Log;

use Psr\Log\LoggerInterface;

/**
 * Use to quickly implement the LoggerAwareInterface
 *
 * @author Theodoros Papadopoulos
 */
trait LoggerAwareTrait
{
    /**
     * A PSR-3 logger. Nulls are allowed, as this effectively disables logging
     *
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /**
     * Attach a logger
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
