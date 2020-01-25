<?php

class Category extends Model
{
	// protected static $table = 'categories';

	protected static $parent_id = 0;
	protected static $id_category = 0;

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	protected static function setProperties()
	{
		self::$properties['id_category'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['parent_id'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['status'] = [
			'type' => 'int',
			'size' => 11
		];

		self::$properties['name'] = [
			'type' => 'varchar',
			'size' => 50
		];
		
		self::$properties['imgSrc'] = [
			'type' => 'varchar',
			'size' => 70
		];
	}

	public static function set($parameter, $value)
	{
		self::$$parameter = $value;
	}

	public static function get($parameter)
	{
		return self::$$parameter;
	}

	public function getParentCategories()
	{
		self::$parent_id = 0;
		return self::getCategories();
	}

	public function getCategories()
	{
		return DB::Instance()->Select(
			'SELECT id_category, `name`, imgSrc, parent_id 
			FROM categories 
			WHERE `status`=:status 
			AND parent_id = :parent_id',
			['status' => Status::Active, 'parent_id' => self::$parent_id]
		);
	}

	public function getCategory()
	{
		return DB::Instance()->SelectAssoc(
			'SELECT id_category, `name`, imgSrc, parent_id 
			FROM categories 
			WHERE `status`=:status 
			AND id_category = :id_category',
			['status' => Status::Active, 'id_category' => (int) self::$id_category]
		);
	}

	public static function getAllCategories()
	{
		return DB::Instance()->Select(
			'SELECT * FROM categories 
			WHERE `status`=:status',
			['status' => Status::Active]
		);
	}
}
