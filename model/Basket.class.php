<?php

class Basket extends Model
{
	// protected static $properties = [];
	protected static $id_good;
	protected static $price;
	protected static $is_in_order;
	protected static $id_order;

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	public static function set($parameter, $value)
	{
		self::$$parameter = $value;
	}

	public static function getBasketGoods()
	{
		return DB::Instance()->getRows(
			"SELECT
			orders.`id_order`, goods.`imgSrc`, goods.`name`, basket.`is_in_order`, goods.`price`, basket.`price` total
			FROM `basket`
			INNER JOIN `orders`
			USING(`id_order`)
			INNER JOIN `goods`
			USING(`id_good`)
			WHERE id_order = :id_order",
			['id_order' => self::$id_order]
		);
	}

	public static function save()
	{
		DB::Instance()->Insert(
			"INSERT INTO `basket`(`id_good`, `price`, `is_in_order`, `id_order`) 
			VALUES (:id_good, :price, :is_in_order, :id_order)",
			['id_good' => self::$id_good,
			'price' => self::$price,
			'is_in_order' => self::$is_in_order,
			'id_order' => self::$id_order]);
	}
}
