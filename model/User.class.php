<?php

class User extends Model
{
	protected static $table = 'users';
	protected static $user_login;
	protected static $user_password;
	protected static $user_name;
	protected static $user_info; // array

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

	public static function get($parameter)
	{
		return self::$$parameter;
	}

	public static function set($parameter, $value)
	{
		self::$$parameter = $value;
	}

	public static function setUserInfo()
	{
		self::$user_info = DB::Instance()->SelectAssoc(
			"SELECT * FROM users 
			WHERE user_login = :user_login",
			['user_login' => self::$user_login]
		);
	}

	/**
	 * Проверка прав пользователя для показа блоков (например в меню, смотреть пункт role в navbar.php)
	 * @param string $role
	 * @return bool
	 */
	// function userHasRole(string $role)
	// {
	// 	if ($role == '?') return !self::isLoggedUser(); // гость
	// 	if ($role == '@') return self::isLoggedUser(); // авторизован
	// 	if ($role == 'admin') return self::isAdmin(); // админ

	// 	return false;
	// }

	public function getUserRole()
	{
		return DB::Instance()->SelectAssoc(
			"SELECT `role_name` 
			FROM user_role, users, roles 
			WHERE user_role.id_user = users.id_user 
			AND user_role.id_role = roles.id_role
			AND users.user_login = :user_login",
			['user_login' => self::$user_login]
		);
	}

	/**
	 * Добавление пользователя
	 */
	public static function addUser()
	{
		// хешируем пароль
		self::$user_password = password_hash(self::$user_password, PASSWORD_DEFAULT);

		$id = DB::Instance()->Insert(
			"INSERT INTO users (user_login, `user_name`, user_password) 
			VALUES (:user_login, :user_name, :user_password)",
			['user_login' => self::$user_login, 'user_name' => self::$user_name, 'user_password' => self::$user_password]
		);

		return DB::Instance()->Insert(
			"INSERT INTO user_role (id_user) 
			VALUES (:id_user)",
			['id_user' => $id]
		);
	}
}
