<?php
require_once "tests/BaseTest.php";

class CategoriesControllerTest extends BaseTest
{
    /**
     * @dataProvider providerCategoriesController
     */
    public function testIndex($a){
        $cc = new CategoriesController();
        $cc_result = $cc->index($a);

        $this->assertNotNull($cc_result);
        $this->assertArrayHasKey("subcategories", $cc_result);
        $this->assertArrayHasKey("goods", $cc_result);
    }

    public function providerCategoriesController(){
        return array (
            array (["id" => 0]),
            array (["id" => 1]),
            array (["id" => 2])
        );
    }
}