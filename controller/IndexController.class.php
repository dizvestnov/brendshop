<?php

class IndexController extends Controller
{
    public $view = 'index';	
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->siteCatalogTitle = 'Fetured Items';
        $this->siteCatalogDescription = 'Shop for items based on what we featured in this week';
        $this->siteCatalogButton = 'Browse All Product';
    }
	
	//метод, который отправляет в представление информацию в виде переменной content_data
	function index($data){
		return ['categories' => Category::getCategories(0), 'siteCatalogTitle' => $this->siteCatalogTitle, 'siteCatalogDescription' => $this->siteCatalogDescription, 'siteCatalogButton' => $this->siteCatalogButton];
	}
}