<?php

class Controller
{
	public $view = 'admin';
	public $title;

	function __construct()
	{
		$this->title = Config::get('sitename');

		// new Basket();
		// new Category();
		// new Good();
		// new Order();
		// new Page();
		// new Review();
		// new User();

		if (Auth::isLoggedUser()) {
			User::set('user_login', $_SESSION['auth']['login']);
			User::setUserInfo();
		}
	}

	public function index($data)
	{
		User::getVisitedPages($this->title);

		return [];
	}

	public static function getVisitedPages($title)
	{
		if (Auth::isLoggedUser()) {

			$orderuri = "/(.*)order(.*)/";
			$useruri = "/(.*)user/";
			$uri = $_SERVER['REQUEST_URI'];

			if (
				!preg_match($orderuri, $uri)
				&& !preg_match($useruri, $uri)
				&& $uri != '/'
				&& $uri != '/?path=categories'
			) {
				$sessionUriArr = array();

				// если сессия page не пустая
				if (isset($_SESSION['pages'])) {
					foreach ($_SESSION['pages'] as $item) {
						$sessionUriArr[] = $item['uri'];
					}
					if (!in_array($uri, $sessionUriArr)) {
						// если колличество страниц меньше 10
						if (count($_SESSION['pages']) < 10) {

							$_SESSION['pages'][] = ['uri' => $_SERVER['REQUEST_URI'], 'title' => $title];
						} else {

							for ($i = 1; $i < 10; $i++) {
								if (!in_array($uri, $_SESSION['pages'][$i - 1])) {
									$_SESSION['pages'][$i - 1] = $_SESSION['pages'][$i];
								}
							}

							$_SESSION['pages'][9]['uri'] = $_SERVER['REQUEST_URI'];
							$_SESSION['pages'][9]['title'] = $title;
						}
					}
				} else {

					$_SESSION['pages'] = array();
					$_SESSION['pages'][] = ['uri' => $_SERVER['REQUEST_URI'], 'title' => $title];
				}
			}
		}
	}
}
