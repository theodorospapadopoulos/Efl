<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Efl\Core\Autoloader;

/**
 * Used to make the Autoloader testable
 * by exposing some functions and altering the behavior of others
 *
 * @author dev
 */
final class AutoloaderExposer extends Autoloader
{
    /**
     * @var string[] $files
     */
    private array $files;
    
    public function __construct()
    {
        $this->files = [];
    }
    
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }
    
    protected function fileExists(string $file): bool
    {
        return in_array($file, $this->files);
    }
    
    public function getClassPath(string $class): ?string
    {
        return parent::getClassPath($class);
    }
}
