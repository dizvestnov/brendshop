<?php

interface IMigration
{
    public static function run();
    public static function getName();
}