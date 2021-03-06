<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit13ce9f465c8fefcfff39961ebeed7dce
{
    public static $files = array (
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'core\\base\\' => 10,
        ),
        'a' => 
        array (
            'app\\models\\' => 11,
            'app\\controllers\\' => 16,
            'app\\components\\' => 15,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Validator\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'core\\base\\' => 
        array (
            0 => __DIR__ . '/..' . '/core/base',
        ),
        'app\\models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'app\\controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controllers',
        ),
        'app\\components\\' => 
        array (
            0 => __DIR__ . '/../..' . '/components',
        ),
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Validator\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/validator',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit13ce9f465c8fefcfff39961ebeed7dce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit13ce9f465c8fefcfff39961ebeed7dce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit13ce9f465c8fefcfff39961ebeed7dce::$classMap;

        }, null, ClassLoader::class);
    }
}
