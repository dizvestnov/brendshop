<?php
class DB
{
	private static $instance = null;
	private $db;

	/**
	 * @return \PDO
	 */
	public static function Instance()
	{
		if (self::$instance === null) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	private function __construct()
	{ }
	private function __sleep()
	{ }
	private function __wakeup()
	{ }
	private function __clone()
	{ }

	public function Connect($user = 'root', $password = '', $base = 'test', $host = 'localhost', $charset = 'UTF-8', $port = 3306)
	{
		$dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $base . ';charset=' . $charset . ';';
		$opt = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => TRUE,
		);
		$this->db = new PDO($dsn, $user, $password, $opt);
	}
	/**
	 * 
	 * @param string $sql
	 * @param array $args
	 * @return \PDOStatement
	 */
	private function sql($sql, $args = [])
	{
		// echo "<pre>".$sql."</pre>";
		$stmt = $this->db->prepare($sql);
		$stmt->execute($args);
		return $stmt;
	}

	/**
	 * 
	 * @param string $sql
	 * @param array $args
	 * @return array
	 */
	public function getRows($sql, $args = [])
	{
		return self::sql($sql, $args)->fetchAll();
	}

	/**
	 * 
	 * @param string $sql
	 * @param array $args
	 * @return array
	 */
	public function getRow($sql, $args = [])
	{
		return self::sql($sql, $args)->fetch();
	}

	/**
	 * 
	 * @param string $sql
	 * @param array $args
	 * @return integer ID
	 */
	public function Insert($sql, $args = [])
	{
		self::sql($sql, $args);
		return $this->db->lastInsertId();
	}

	/**
	 * 
	 * @param string $sql
	 * @param array $args
	 * @return integer affected rows
	 */
	public function update($sql, $args = [])
	{
		$stmt = self::sql($sql, $args);
		return $stmt->rowCount();
	}

	/** 
	 * 
	 * @param string $sql
	 * @param array $args
	 * @return integer affected rows
	 */
	public function Delete($sql, $args = [])
	{
		$stmt = self::sql($sql, $args);
		return $stmt->rowCount();
	}

	/*
     * Выполнить запрос к БД
     */
	public function Query($query, $params = array())
	{
		$res = $this->db->prepare($query);
		$res->execute($params);
		return $res;
	}

	/*
     * Выполнить запрос с выборкой данных
     */
	public function Select($query, $params = array())
	{
		$result = $this->Query($query, $params);
		if ($result) {
			return $result->fetchAll();
		}
	}

	public function SelectAssoc($query, $params = array())
	{
		$result = $this->Query($query, $params);
		if ($result) {
			return $result->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function getProduct($table, $id)
	{
		$id = (int) $id;
		$table = mysqli_real_escape_string($this->connect, $table);
		$sql = "SELECT * FROM %s WHERE id='%d'";
		$query = sprintf($sql, $table, $id);
		$res = mysqli_query($this->connect, $query);
		if (!$res)
			die(mysqli_error($this->connect));
		$t = mysqli_num_rows($res);
		$product = [];
		$product[] = mysqli_fetch_assoc($res);
		return $product;
	}
}
