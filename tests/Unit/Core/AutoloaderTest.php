<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\AutoloaderExposer;

/**
 * @author Theodoros Papadopoulos
 * @group Core
 */
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

    /**
     * @small
     * @return void
     */
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

    /**
     * @small
     * @return void
     */
    public function testMultipleDirectoriesPerNamespace(): void
    {
        $this->loader->mapNamespaceMultiple('Efl', ['/home/dev/efl/src/', '/home/dev/efl/src/Core']);
        
        $classpath = $this->loader->getClassPath('Efl\\Application');
        $this->assertSame($classpath, '/home/dev/efl/src/Application.php');
        
        $classpath = $this->loader->getClassPath('Efl\\Logger');
        $this->assertSame($classpath, '/home/dev/efl/src/Core/Logger.php');
    }

    /**
     * @small
     * @return void
     */
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

    /**
     * @small
     * @return void
     */
    public function testFailureToFindClasspath(): void
    {
        $this->loader->mapNamespace('\\Efl', '/home/dev/efl/src/Core/');
        
        $classpath = $this->loader->getClassPath('Efl\\Application');
        $this->assertNull($classpath);
        
        $classpath = $this->loader->getClassPath('Efl\\Services\\Cache\\Redis');
        $this->assertNull($classpath);
    }
}