<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1428781cbc84a446474e218c48f1d085
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Alban\\LaravelDataSync\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Alban\\LaravelDataSync\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1428781cbc84a446474e218c48f1d085::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1428781cbc84a446474e218c48f1d085::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1428781cbc84a446474e218c48f1d085::$classMap;

        }, null, ClassLoader::class);
    }
}