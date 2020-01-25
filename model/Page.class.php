<?php

class Page extends Model
{

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	protected static function setProperties()
	{
		self::$properties['name'] = [
			'type' => 'varchar',
			'size' => 512
		];

		self::$properties['url'] = [
			'type' => 'varchar',
			'size' => 512
		];
	}

	public function getPages($parentId = 0)
	{
		return DB::Instance()->Select(
			"SELECT id, name, url FROM pages WHERE status=:status AND parent_id = :parent_id",
			['status' => Status::Active, 'parent_id' => $parentId]
		);
	}
}
