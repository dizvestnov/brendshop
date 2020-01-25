<?php
class CategoriesController extends Controller
{
	public $view = 'categories';

	// разобраться что в этом методе
	public function index($data)
	{
		// new Category();
		$id = isset($data['id']) ? $data['id'] : 0;
		Category::set('id_category', $id);
		Category::set('parent_id', $id);
		Good::set('id_category', $id);
		$this->title = Category::getCategory()['name'];
		parent::getVisitedPages($this->title);

		return [
			'categories' => Category::getAllCategories(),
			'subcategories' => Category::getCategories(),
			'category' => Category::getCategory(),
			'category_id' => Category::get('id_category'),
			'goods' => Good::getGoods(),
			'view' => $this->view
		];
	}

	public function good($data)
	{
		if ($data['id'] > 0) {
			$good = new Good([
				"id_good" => $data['id'],
			]);
			$goodInfo = $good->getGoodInfo();
			$goodPhoto = $good->getGoodPhoto();
			$goods = $good->getGoods();
			shuffle($goods);
			$goods = array_slice($goods, 0, 4);
			Good::set('id_category', $data['id']);
			Category::set('id_category', (int) $goodInfo['id_category']);
			$this->title = $goodInfo['name'];
			parent::getVisitedPages($this->title);

			return [
				'categories' => Category::getAllCategories(),
				'category' => Category::getCategory(),
				'good' => $goodInfo,
				'good_photos' => $goodPhoto,
				'goods' => $goods
			];
		} else {
			header("Location: /categories/");
		}
	}

	public function getGoods($data)
	{
		$_GET['asAjax'] = true;
		Good::set('id_category', $data['id']);
		echo json_encode(Good::getGoods());
		exit;
	}
}
