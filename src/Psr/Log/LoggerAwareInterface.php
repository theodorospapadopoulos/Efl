<?php

declare(strict_types=1);

namespace Psr\Log;

/**
 * Represents an entity that can attach and use a logger
 * as defined in PSR-3
 *
 * @author Theodoros Papadopoulos
 */
interface LoggerAwareInterface
{
    /**
     * Attach a logger. Null is allowed to effectively disable the logger
     *
     * @param ?LoggerInterface $logger
     * @return void
     */
    public function setLogger(?LoggerInterface $logger): void;
}
