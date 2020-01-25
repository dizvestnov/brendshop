<?php

class Order extends Model
{
	// protected static $table = 'orders';
	// protected static $properties = [];
	protected static $id_user = 2;
	protected static $id_order;
	protected static $amount = 0;
	protected static $id_order_status = 1;

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	protected static function setProperties()
	{
		self::$properties['id_user'] = [
			'type' => 'int',
			'size' => 11
		];
		self::$properties['amount'] = [
			'type' => 'double'
		];
		self::$properties['datetime_create'] = [
			'type' => 'datetime'
		];
		self::$properties['id_order_status'] = [
			'type' => 'int',
			'size' => 11
		];
	}

	public static function get($parameter)
	{
		return self::$$parameter;
	}

	public static function set($parameter, $value)
	{
		self::$$parameter = $value;
	}

	public static function createOrder()
	{
		if (Auth::isLoggedUser()) {
			self::$id_user = $_SESSION['auth']['id'];
		}

		if (isset($_SESSION['cart'])) {
			self::$id_order = DB::Instance()->Insert(
				"INSERT INTO `orders` (id_user) 
				VALUES (:id_user)",
				['id_user' => self::$id_user]
			);
			self::setOrderInfo();
			if (isset(self::$id_order)) {
				foreach ($_SESSION['cart'] as $id => $item) {
					$price = (float) $item['price'] * (int) $item['quantity'];
					self::$amount = self::$amount + $price;
					Basket::set('id_good', $id);
					Basket::set('price', $price);
					Basket::set('is_in_order', $item['quantity']);
					Basket::set('id_order', self::$id_order);
					Basket::save();
					unset($_SESSION['cart'][$id]);
				}

				self::addAmountToOrder();
				self::addOrderStatus();
			}

			if (Auth::isLoggedUser()) {
				header("Location: /?path=user");
				exit;
			}
			header("Location: /");
		}
	}

	public static function setOrderInfo()
	{
		return DB::Instance()->Insert(
			"INSERT INTO `order_info` 
			(id_order, first_name, last_name, `address`, city, `state`, phone_number) 
			VALUES (:id_order, :first_name, :last_name, :address, :city, :state, :phone_number)",
			[
				'id_order' => self::$id_order,
				'first_name' => $_POST['first_name'],
				'last_name' => $_POST['last_name'],
				'address' => $_POST['address'],
				'city' => $_POST['city'],
				'state' => $_POST['state'],
				'phone_number' => $_POST['phone_number']
			]
		);
	}

	public static function getOrderInfo()
	{
		return DB::Instance()->getRow(
			"SELECT
			*
			FROM `order_info`
			INNER JOIN `orders`
			USING(`id_order`)
			WHERE id_order = :id_order",
			['id_order' => self::$id_order]
		);
	}

	public function addAmountToOrder()
	{
		return DB::Instance()->update(
			"UPDATE `orders`
			SET amount = :amount
			WHERE id_order = :id_order",
			[
				'amount' => self::$amount,
				'id_order' => self::$id_order
			]
		);
	}

	public function addOrderStatus()
	{
		return DB::Instance()->update(
			"UPDATE `orders`
			SET id_order_status = :id_order_status
			WHERE id_order = :id_order",
			[
				'id_order_status' => self::$id_order_status,
				'id_order' => self::$id_order
			]
		);
	}

	public function getOrders()
	{
		return DB::Instance()->getRows(
			"SELECT
			`id_order`, `amount`, `datetime_create`, `order_status_name`, `user_name`
			FROM `orders`
			INNER JOIN `users`
			USING(`id_user`)
			INNER JOIN `order_status`
			USING(`id_order_status`)",
			[]
		);
	}

	public function getUserOrders()
	{
		return DB::Instance()->getRows(
			"SELECT * 
			FROM `orders`
			INNER JOIN `order_status`
			USING(`id_order_status`)
			INNER JOIN `users`
			USING(`id_user`)
			WHERE `id_user`=:id_user",
			['id_user' => self::$id_user]
		);
	}

	public function getOrderStatus()
	{
		return DB::Instance()->getRow(
			"SELECT `order_status_name`
			FROM `orders`
			INNER JOIN `order_status`
			USING(`id_order_status`)
			WHERE id_order = :id_order",
			[
				'id_order' => self::$id_order
			]
		);
	}

	public function removerOrders()
	{
		return DB::Instance()->Delete(
			"DELETE FROM `orders` 
			WHERE `id_order`=:id_order",
			['id_order' => self::$id_order]
		);
	}
}
