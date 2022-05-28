<?php

declare(strict_types=1);

namespace Tests\Core;

use \PHPUnit\Framework\TestCase;

require_once(dirname(dirname(dirname(__FILE__))) . '/src/Core/Autoloader.php');

final class AutoloaderExposer extends \Efl\Core\Autoloader
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

final class AutoloaderTest extends TestCase
{
    private AutoloaderExposer $loader;
    
    protected function setUp(): void
    {
        $this->loader = new AutoloaderExposer();
        $this->loader->setFiles([
            '/home/dev/efl/src/Application.php', 
            '/home/dev/efl/src/Core/Logger.php', 
            '/home/dev/efl/src/Services/Database/MySql.php',
            '/home/dev/efl/src/Services/Cache/Redis.php',
        ]);
    }
    
    public function testSimpleRootNamespaceWithSubdirs(): void
    {
        $this->loader->mapNamespace('\\Efl\\', '/home/dev/efl/src');
        
        $classpath = $this->loader->getClassPath('Efl\\Application');
        $this->assertSame($classpath, '/home/dev/efl/src/Application.php');
        
        $classpath = $this->loader->getClassPath('\\Efl\\Core\\Logger');
        $this->assertSame($classpath, '/home/dev/efl/src/Core/Logger.php');
        
        $classpath = $this->loader->getClassPath('\\Efl\\Services\\Database\\MySql');
        $this->assertSame($classpath, '/home/dev/efl/src/Services/Database/MySql.php');
    }
    
    public function testMultipleDirectoriesPerNamespace(): void
    {
        $this->loader->mapNamespaceMultiple('Efl', ['/home/dev/efl/src/', '/home/dev/efl/src/Core']);
        
        $classpath = $this->loader->getClassPath('Efl\\Application');
        $this->assertSame($classpath, '/home/dev/efl/src/Application.php');
        
        $classpath = $this->loader->getClassPath('Efl\\Logger');
        $this->assertSame($classpath, '/home/dev/efl/src/Core/Logger.php');
    }
    
    public function testDeepNamespaceAliases(): void
    {
        $this->loader->mapNamespace('Efl\\DB', '/home/dev/efl/src/Services/Database/')
                     ->mapNamespace('Efl\\Srv\\', '/home/dev/efl/src/Services')
                     ->mapNamespace('Efl', '/home/dev/efl/src');
                     
        $classpath = $this->loader->getClassPath('Efl\\DB\MySql');
        $this->assertSame($classpath, '/home/dev/efl/src/Services/Database/MySql.php');
        
        $classpath = $this->loader->getClassPath('Efl\\Srv\\Cache\\Redis');
        $this->assertSame($classpath, '/home/dev/efl/src/Services/Cache/Redis.php');
        
        $classpath = $this->loader->getClassPath('Efl\\Application');
        $this->assertSame($classpath, '/home/dev/efl/src/Application.php');
    }
    
    public function testFailureToFindClasspath(): void
    {
        $this->loader->mapNamespace('\\Efl', '/home/dev/efl/src/Core/');
        
        $classpath = $this->loader->getClassPath('Efl\\Application');
        $this->assertNull($classpath);
        
        $classpath = $this->loader->getClassPath('Efl\\Services\\Cache\\Redis');
        $this->assertNull($classpath);
    }
}