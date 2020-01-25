<?php

class OrderController extends Controller
{
	function updateCart()
	{
		$_GET['asAjax'] = true;
		if (!empty($_SESSION['cart'])) echo json_encode($_SESSION['cart']);
	}

	function updateGoodCount()
	{
		$_GET['asAjax'] = true;
		$id_good = $_POST['id_good'];
		$count = $_POST['count'];
		$_SESSION['cart'][$id_good]['quantity'] = $count;
		return true;
	}

	function removeGoodFromCart($id_good)
	{
		$id_good = $_POST['id_good'];
		$quantity = $_SESSION['cart'][$id_good]['quantity'];
		unset($_SESSION['cart'][$id_good]);
		if ($quantity != 0) {
			$good = new Good([
				"id_good" => $id_good,
				"quantity" => $quantity
			]);
			$good->updateGoodQuantity('+');
		}
		return json_encode($_SESSION['cart']);
		exit;
	}
}
