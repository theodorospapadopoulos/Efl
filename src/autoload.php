<?php

declare(strict_types=1);

$EFL_ROOT = dirname(__FILE__) . DIRECTORY_SEPARATOR;
require($EFL_ROOT . 'Core/Autoloader.php');

(new \Efl\Core\Autoloader())
    ->mapNamespace('Efl', $EFL_ROOT)
    ->mapNamespace('Psr', $EFL_ROOT . 'Psr')
    ->mapNamespace('Tests', dirname($EFL_ROOT) . '/tests')
    ->register();
