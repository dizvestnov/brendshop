<?php
class AdminController extends Controller
{

	protected $controls = [
		'orders' => 'Orders',
		'categories' => 'Categories',
		'goods' => 'Discounts and promotions'
	];


	public function index($data)
	{
		$this->title = 'Admin panel';

		Auth::onlyAdmin();
		$orders = Order::getOrders();

		return [
			'controls' => $this->controls,
			'orders' => $orders
		];
	}

	public function getOrderInfo()
	{
		$_GET['asAjax'] = true;
		Order::set('id_order', $_POST['id_order']);
		echo json_encode(Order::getOrderinfo());
	}

	public function getOrderGoods()
	{
		$_GET['asAjax'] = true;
		Basket::set('id_order', $_POST['id_order']);
		$basket = Basket::getBasketGoods();
		// foreach($basket as $item){
		// 	$good = new Good([
		// 		"id_good" => $item['id_good'],
		// 	]);
		// 	$basketGoods[] = $good->getGoodsInfo();
		// }
		echo json_encode($basket);
	}

	function updateOrderStatus()
	{
		$_GET['asAjax'] = true;
		Order::set('id_order', $_POST['id_order']);
		Order::set('id_order_status', 2);
		if (Order::addOrderStatus()) {
			echo json_encode([
				'status' => 'OK',
				'order_status' => Order::getOrderStatus()['order_status_name'],
				'message' => 'Save'
			]);
		} else {
			echo json_encode([
				'status' => 'ERROR',
				'message' => 'Some thing wrong'
			]);
		}
	}

	function removeOrder()
	{
		$_GET['asAjax'] = true;
		Order::set('id_order', $_POST['id_order']);
		if (Order::removerOrders()) {
			echo json_encode([
				'status' => 'OK',
				'message' => 'Remove'
			]);
		} else {
			echo json_encode([
				'status' => 'ERROR',
				'message' => 'Some thing wrong'
			]);
		}
	}
}
