<?php

class M001 implements IMigration
{
    public static function run()
    {
        Logger::write('Just to test it works', true);
    }

    public static function getName()
    {
        return __CLASS__;
    }
}