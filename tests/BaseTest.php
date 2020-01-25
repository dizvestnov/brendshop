<?php
require_once "autoload.php";

abstract class BaseTest extends PHPUnit_Framework_TestCase{
    protected function setUp()
    {
        App::Init();
    }

}