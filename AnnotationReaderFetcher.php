<?php

namespace AC\KalinkaBundle;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class AnnotationReaderFetcher
{
    private static $reader;
    private static $readerFetchFunc;

    public static function getReader()
    {
        if (!isset(static::$reader)) {
            if (isset(static::$readerFetchFunc)) {
                static::$reader = call_user_func(static::$readerFetchFunc);
            } else {
                $loader = require 'vendor/autoload.php';
                AnnotationRegistry::registerLoader([$loader, 'loadClass']);
                // AnnotationRegistry::registerAutoloadNamespace(
                //     'JMS\Serializer\Annotation',
                //     "/vendor/jms/serializer/src"
                // );
                // AnnotationRegistry::registerAutoloadNamespace(
                //     'AC\KalinkaBundle\Annotation',
                //     "/Annotations"
                // );
                static::$reader = new AnnotationReader;
            }
        }

        return static::$reader;
    }

    public static function setReaderFetchFunc($r)
    {
        static::$readerFetchFunc = $r;
    }
}
