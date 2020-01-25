<?php

class OrderGood extends Model
{
	protected static $table = 'orders_goods';

	protected static function setProperties()
	{
		self::$properties['order_id'] = [
			'type' => 'int',
		];

		self::$properties['good_id'] = [
			'type' => 'int',
		];
	}
}
