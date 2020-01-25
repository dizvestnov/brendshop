<?php

class Auth extends Model
{
	private static $path = '/?path=';
	private static $page = 'user';

	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	public static function get($parameter)
	{
		return self::$$parameter;
	}

	public static function set($parameter, $value)
	{
		self::$$parameter = $value;
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

	public static function regUser()
	{
		User::set('user_login', $_POST['login']);
		User::set('user_name', $_POST['name']);
		User::set('user_password', $_POST['password']);
		// готовим запрос SQL
		if (User::addUser()) {
			// авторизуем
			User::setUserInfo();
			self::login(true);
		}
	}

	public static function loginUser()
	{
		User::set('user_login', $_POST['login']);
		User::setUserInfo();
		// Если в базе есть юзер с таким логином
		if (User::get('user_info')) {
			// Если хеш-пароль из базы совпал с введенным паролем
			if (self::checkUserPassword()) {
				if (isset($_POST['remember'])) {
					self::login(true);
				} else {
					self::login();
				}
			}
		}
	}

	/**
	 * Авторизация пользователя
	 * @param bool $remember
	 */
	public static function login(bool $remember = false)
	{
		// загружаем пользователя из БД
		$user = User::get('user_info');

		// запоминаем логин в сессии
		$_SESSION['auth'] = [
			'id' => $user['id_user'],
			'login' => $user['user_login'],
		];

		// если логин = admin
		if (User::getUserRole()['role_name'] == 'admin') {
			// даем дополнительные права
			$_SESSION['auth']['admin'] = true;
		}
		if (User::getUserRole()['role_name'] == 'editor') {
			// даем дополнительные права
			$_SESSION['auth']['editor'] = true;
		}

		// если поставил "запомнить меня"
		if ($remember) {
			$auth = [
				'login' => $_SESSION['auth']['login'],
			];
			self::setCook('auth', json_encode($auth));
		}

		header('Location: ' . self::$path . self::$page);
	}

	public static function checkUserPassword()
	{
		return password_verify($_POST['password'], User::get('user_info')['user_password']);
	}

	/**
	 * Попытка загрузки авторизации через COOKIES
	 */
	function autoLogin()
	{
		if (isset($_COOKIE['auth'])) {
			$auth = json_decode($_COOKIE['auth'], true);

			self::login($auth['login']);
		}
	}

	/**
	 * Выход из системы
	 */
	function logoutUser()
	{
		unset($_SESSION['auth']);
		unset($_SESSION['pages']);
		// надо сохранить в БД или сессию
		// unset($_SESSION['cart']);
		header("Location: /");
	}

	function isLoggedUser()
	{
		if (isset($_SESSION['auth']['login'])) {
			User::set('user_login', $_SESSION['auth']['login']);
			User::setUserInfo();
			return true;
		} else return false;
	}

	/**
	 * Функция для открытия доступа только авторизованным пользователям
	 * 
	 * Сделать метод в Controller.class
	 * если надо не на индексную страницу редирект
	 * то в дочернем классе надо вызвать метод родителя
	 * и задать page
	 * 
	 * А может и все проще сделаю
	 */
	public static function onlyAuth($page = '/')
	{
		if (!self::isLoggedUser()) {
			header("Location: " . $page);
		}
	}

	/**
	 * Проверка на администратора (смотреть в функции логина)
	 * @return bool
	 */
	function isAdmin()
	{
		return (isset($_SESSION['auth']['admin']) && $_SESSION['auth']['admin']);
	}

	/**
	 * Функция для открытия доступа только администраторам
	 */
	function onlyAdmin()
	{
		if (!self::isAdmin()) {
			header("Location: /");
		}
	}

	function isEditor()
	{
		return (isset($_SESSION['auth']['editor']) && $_SESSION['auth']['editor']);
	}


	function onlyEditor()
	{
		if (!self::isEditor()) {
			header("Location: /");
		}
	}

	/**
	 * Функция для упрощения записи COOKIES
	 * @param string $key
	 * @param $value
	 */
	function setCook(string $key, $value)
	{
		setcookie(
			$key,
			$value,
			time() + 3600 * 2, //seconds
			'/',
			Config::get('sitehost'),
			true,
			true
		);
	}

	/**
	 * Функция для сброса значения COOKIES
	 * @param string $key
	 */
	function resetCook(string $key)
	{
		setcookie(
			$key,
			'',
			0,
			'/',
			Config::get('sitehost'),
			true,
			true
		);
	}
}
