<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb59b7ed2798c4ae849c7669047771e59
{
    public static $prefixLengthsPsr4 = array (
        'e' => 
        array (
            'eftec\\bladeone\\' => 15,
        ),
        'S' => 
        array (
            'Symfony\\Component\\Dotenv\\' => 25,
        ),
        'L' => 
        array (
            'Lecon\\Mvcoop\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'eftec\\bladeone\\' => 
        array (
            0 => __DIR__ . '/..' . '/eftec/bladeone/lib',
        ),
        'Symfony\\Component\\Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/dotenv',
        ),
        'Lecon\\Mvcoop\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'B' => 
        array (
            'Bramus' => 
            array (
                0 => __DIR__ . '/..' . '/bramus/router/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb59b7ed2798c4ae849c7669047771e59::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb59b7ed2798c4ae849c7669047771e59::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb59b7ed2798c4ae849c7669047771e59::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb59b7ed2798c4ae849c7669047771e59::$classMap;

        }, null, ClassLoader::class);
    }
}
