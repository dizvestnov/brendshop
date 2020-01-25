<?php
require_once "tests/BaseTest.php";

class CategoryTest extends BaseTest
{
    public function testGetCategories(){
        $this->assertNotNull(Category::getCategories());
    }
}

