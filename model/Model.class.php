<?php

abstract class Model
{

	protected static $table;
	protected static $properties = [
		'id' => [
			'type' => 'int',
			'autoincrement' => true,
			'readonly' => true,
			'unsigned' => true
		],
		'created_at' => [
			'type' => 'datetime',
			'readonly' => true,
		],
		'updated_at' => [
			'type' => 'datetime',
			'readonly' => true,
		],
		'status' => [
			'type' => 'int',
			'size' => 2,
			'unsigned' => true
		]
	];

	public function __construct(array $values)
	{
		static::setProperties();

		static::setTable();

		foreach ($values as $name => $value) {
			$this->$name = $value;
		}
	}

	public static function setTable()
	{
		$className = strtolower(static::class);
		$classLastSimbol = '/(.*)y/';
		if(preg_match($classLastSimbol , $className)){
			$className = substr_replace($className, 'ies', -1);
		} else $className = $className . 's';
		
		return self::$table = $className;
	}

	/**
	 * Вызывается в конструкторе и при генерации, чтобы дополнить базовый набор свойств
	 */
	protected static function setProperties()
	{
		return true;
	}

	public final static function generate()
	{
		if (self::tableExists()) throw new Exception('Table already exists');
		static::setProperties();
		$query = 'CREATE TABLE ' . self::$table . ' (';
		foreach (static::$properties as $property => $params) {
			if (!isset($params['type'])) {
				throw new Exception('Property ' . $property . 'has no type');
			}
			$query .= ' `' . $property . '`';

			$query .= ' ' . $params['type'];
			if (isset($params['size'])) {
				$query .= '(' . $params['size'] . ')';
			}

			if (isset($params['unsigned']) && $params['unsigned']) {
				$query .= ' UNSIGNED';
			}

			if (isset($params['autoincrement']) && $params['autoincrement']) {
				$query .= ' AUTO_INCREMENT';
			}
			$query .= ',' . "\n";
		}
		$query .= ' PRIMARY KEY (`id`))';
		DB::Instance()->Query($query);
		return true;
	}

	public function __get($name)
	{
		$this->checkProperty($name);
		$return = null;

		switch (static::$property['type']) {
			case 'int':
				return (int) $this->$name;
				// break;
			case 'double':
				return (float) $this->$name;
				// break;
			default:
				return (string) $this->$name;
				// break;
		}
	}

	public function __set($name, $value)
	{
		$this->checkProperty($name);
		switch (static::$properties[$name]['type']) {
			case 'int':
				$this->$name = (int) $value;
				break;
			case 'double':
				$this->$name = (float) $value;
				break;
			default:
				$this->$name = (string) $value;
				break;
		}
		if (isset(static::$properties[$name]['size'])) {
			$this->$name = mb_substr($this->$name, 0, static::$properties[$name]['size']);
		}
	}

	protected final static function tableExists()
	{
		return count(DB::Instance()->select('SHOW TABLES LIKE "' . self::$table . '"')) > 0;
	}

	protected final function checkProperty($name)
	{
		if (!isset(static::$properties[$name])) {
			throw new Exception('Undefined property ' . $name);
		}
		if (!isset(static::$properties[$name]['type'])) {
			throw new Exception('Undefined type for property ' . $name);
		}
	}
}
