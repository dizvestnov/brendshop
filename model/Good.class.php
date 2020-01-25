<?php

class Good extends Model
{
	// protected static $table = 'goods';
	protected static $id_category = 0;

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	protected static function setProperties()
	{

		self::$properties['id_good'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['id_category'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['id_brand'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['id_designer'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['status'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['quantity'] = [
			'type' => 'int',
			'size' => 10
		];

		self::$properties['name'] = [
			'type' => 'varchar',
			'size' => 50
		];

		self::$properties['description'] = [
			'type' => 'text'
		];

		self::$properties['price'] = [
			'type' => 'double',
			'size' => 10, 2
		];

		self::$properties['imgSrc'] = [
			'type' => 'varchar',
			'size' => 256
		];
	}

	public static function set($parameter, $value)
	{
		self::$$parameter = $value;
	}

	public static function getGoods()
	{
		if (self::$id_category == 0) {
			$result = DB::Instance()->Select(
				'SELECT id_good, id_category, `name`, price, imgSrc, quantity 
				FROM goods 
				WHERE `status`=:status',
				['status' => Status::Active]
			);
			return $result;
		}
		$result = DB::Instance()->Select(
			'SELECT id_good, id_category, `name`, price, imgSrc, quantity 
			FROM goods 
			WHERE id_category = :category 
			AND `status` = :status 
			UNION 
			SELECT id_good, id_category, `name`, price, imgSrc, quantity 
			FROM goods 
			WHERE id_category 
			IN (SELECT id_category FROM categories WHERE parent_id = :category) 
			AND `status` = :status
			UNION 
			SELECT id_good, id_category, `name`, price, imgSrc, quantity 
			FROM goods 
			WHERE id_category 
			IN (SELECT id_category FROM categories WHERE parent_id 
			IN (SELECT id_category FROM categories WHERE parent_id = :category)) 
			AND `status` = :status',
			['status' => Status::Active, 'category' => self::$id_category]
		);
		return $result;
	}

	public function getGoodsInfo()
	{
		$result = DB::Instance()->Select(
			'SELECT * 
			FROM goods 
			WHERE id_good = :id_good',
			['id_good' => (int) $this->id_good]
		);
		return $result;
	}

	public function getGoodInfo()
	{
		$result = DB::Instance()->SelectAssoc(
			'SELECT *
			FROM goods 
			WHERE id_good=:id_good',
			['id_good' => (int) $this->id_good]
		);
		return $result;
	}

	public function getGoodPhoto()
	{
		return DB::Instance()->getRows(
			'SELECT
			`id_good_imgs`, `imgSrc`
			FROM `good_imgs`
			WHERE `id_good` = :id_good',
			['id_good' => (int) $this->id_good]
		);
	}

	public function updateGoodQuantity($operation)
	{
		$result = DB::Instance()->update(
			"UPDATE goods
			SET quantity = quantity" . "$operation" . ":quantity
			WHERE id_good = :id_good",
			[
				'id_good' => (int) $this->id_good,
				'quantity' => (int) $this->quantity
			]
		);
		return $result;
		exit;
	}
}
