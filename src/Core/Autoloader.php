<?php

declare(strict_types=1);

namespace Efl\Core;

/**
 * Implementation of a PSR-4 autoloader
 * - A class namespace prefix can be mapped to one or more root directories
 * - Sub-namespace names after the namespace prefix correspond
 *   to a subdirectory within the root directory
 * - Supports arbitrary class file extensions (the default is 'php')
 *
 * @author Theodoros Papadopoulos
 */
class Autoloader
{
    /**
     * @var array $nsmaps Holds the mappings of the namespaces to directories
     * @phpstan-var array<string, string[]> $nsmaps
     */
    private array $nsmaps;

    /**
     * @var string $fileExtension The extension of the PHP class files
     */
    private string $fileExtension = 'php';

    /**
     * Autoloader constructor
     */
    public function __construct()
    {
        $this->nsmaps = [];
    }

    /**
     * Relates a namespace with a target base directory.
     * If the namespace already exists, the directory will be appended
     * to the mapping list. Leading or trailing '\' characters
     * in the namespace prefix are stripped.
     *
     * @param string $namespace The namespace
     * @param string $basedir The root directory for the above namespace
     * @return static
     */
    public function mapNamespace(string $namespace, string $basedir): static
    {
        $this->nsmaps[$this->sanitizeNamespace($namespace)][] =
            $this->sanitizeDir($basedir);

        return $this;
    }

    /**
     * Relates a namespace with multiple target base directories.
     * If the namespace exists its directory mappings will be replaced
     * with the ones supplied. Leading or trailing '\' characters
     * in the namespace prefix are stripped.
     *
     * @param string $namespace The namespace
     * @param string[] $basedirs A list of the root directories for the above namespace
     * @return static
     */
    public function mapNamespaceMultiple(string $namespace, array $basedirs): static
    {
        $this->nsmaps[$this->sanitizeNamespace($namespace)] =
            array_map(array($this, 'sanitizeDir'), $basedirs);

        return $this;
    }

    /**
     * @param string $ext The file extension that will be appended to class files
     *        Do not use '.' before the extension
     * @return static
     */
    public function setFileExtension(string $ext): static
    {
        $this->fileExtension = $ext;
        return $this;
    }

    /**
     * Registers the autoloading method. It must be called
     * after the autoloader namespace configuration in order
     * for the autoloader to take effect.
     */
    public function register(): void
    {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * Given a fully qualified class name, it returns
     * a full path to the class file
     *
     * @param string $class Te fully qualified class name
     * @return string|null The full path to the class file or null if not found
     */
    protected function getClassPath(string $class): ?string
    {
        $class = trim($class, " \\");
        $namespace = $this->findNamespace($class);

        if ($namespace !== null) {
            return $this->findClassPath($namespace, $class);
        }

        return null;
    }

    /**
     * Loads the file that corresponds to the provided class name.
     * If not found nothing happens (no error raised).
     *
     * @param string $class the fully qualified class name to load
     */
    protected function load(string $class): void
    {
        $classpath = $this->getClassPath($class);
        if ($classpath !== null) {
            require($classpath);
        }
    }

    /**
     * Strips backslashes from the beginning and end of a namespace
     *
     * @access private
     * @param string $namespace A namespace
     * @return string The sanitized namespace
     */
    private function sanitizeNamespace(string $namespace): string
    {
        return trim($namespace, " \\");
    }

    /**
     * Ensures there is a directory separator at the end of the
     * directory string
     *
     * @access private
     * @param string $dir A directory path
     * @return string The sanitized directory path
     */
    private function sanitizeDir(string $dir): string
    {
        return (rtrim($dir, " " . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
    }

    /**
     * Given a sanitized fully qualified class name,
     * it returns the registered namespace key
     *
     * @access private
     * @param string $class The class name
     * @return string|null The key in the $nsmaps array that
     *      is the closest match to the namespace part of the $class,
     *      or null if not found
     */
    private function findNamespace(string $class): ?string
    {
        do {
            $pos = strrpos($class, '\\');
            if ($pos !== false) {
                $class = substr($class, 0, $pos);
            }

            if (isset($this->nsmaps[$class])) {
                return $class;
            }
        } while ($pos !== false);

        return null;
    }

    /**
     * Finds the full path for a given class and namespace
     *
     * @access private
     * @param string $namespace The namespace key in the $nsmaps array
     * @param string $class The sanitized fully qualified class name
     * @return string|null The base directory of the class or null if not found
     *      or it is not a valid directory path.
     */
    private function findClassPath(string $namespace, string $class): ?string
    {
        $relativeClass = ltrim(str_replace($namespace, '', $class), '\\');
        $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
        if ($this->fileExtension !== '') {
            $relativePath .= '.' . $this->fileExtension;
        }

        foreach ($this->nsmaps[$namespace] as $basedir) {
            $fullPath = $basedir . $relativePath;
            if ($this->fileExists($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }

    /**
     * Checks if a file path exists
     *
     * @param string $file The full file path
     * @return bool True if the file exists
     */
    protected function fileExists(string $file): bool
    {
        return is_file($file);
    }
}
