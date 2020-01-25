<?php

class CartController extends Controller
{
	public $view = 'cart';

	public function index($data)
	{
		$this->title = 'Shopping Cart';

		return [];
	}

	public function checkout($data)
	{
		$this->title = 'Check out';

		$asguest = isset($data['asguest']);

		if (isset($_POST['login_user'])) {
			Auth::set('page', 'cart/checkout');
			Auth::loginUser();
		}

		if (isset($_POST['checkout_continue'])) {
			if ($_POST['checkout_radio'] == 'asguest') {
				header("Location: /?path=cart/checkout&asguest");
			}
			// if ($_POST['checkout_radio'] == 'reg') {
			// 	header("Location: /?path=auth/sign_up");
			// }
		}

		if (isset($_POST['order_info'])) {
			// Order::setTable();
			Order::createOrder();
		}

		return ['title' => $this->title, 'guest' => $asguest];
	}

	function addToCard()
	{
		$_GET['asAjax'] = true;
		if ($_POST['id_good'] !== null) {
			$id_good = $_POST['id_good'];
			if (!empty($_SESSION['cart'][$id_good])) {
				$_SESSION['cart'][$id_good]['quantity'] += $_POST['quantity'];
				$good = new Good([
					"id_good" => $id_good,
					"quantity" => $_POST['quantity']
				]);
				$good->updateGoodQuantity('-');
				echo json_encode($_SESSION['cart']);
			} else {
				$_SESSION['cart'][$id_good] = array();
				$quantity = $_POST['quantity'];
				$good = new Good([
					"id_good" => $id_good,
					"quantity" => $quantity
				]);
				$goods = $good->getGoodInfo();
				$_SESSION['cart'][$id_good]['name'] = $goods['name'];
				$_SESSION['cart'][$id_good]['price'] = $goods['price'];
				$_SESSION['cart'][$id_good]['imgSrc'] = $goods['imgSrc'];
				$_SESSION['cart'][$id_good]['quantity'] = $_POST['quantity'];
				$good->updateGoodQuantity('-');
				echo json_encode($_SESSION['cart']);
			}
		}
	}
}
