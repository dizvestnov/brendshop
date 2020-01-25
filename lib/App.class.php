<?php

class App
{
	public static function Init()
	{
		date_default_timezone_set('Europe/Moscow');
		DB::Instance()->Connect(Config::get('db_user'), Config::get('db_password'), Config::get('db_base'), Config::get('db_host'), Config::get('db_charset'), Config::get('db_port'));

		if (php_sapi_name() !== 'cli' && isset($_SERVER) && isset($_GET)) {
			self::web(isset($_GET['path']) ? $_GET['path'] : '');
		}
	}
	//http://site.ru/index.php?path=news/edit/5
	protected static function web($url)
	{
		/*парсинг строки*/
		$url = explode("/", $url);
		if (!empty($url[0])) {
			$_GET['page'] = $url[0];
			if (isset($url[1])) {
				if (is_numeric($url[1])) {
					$_GET['id'] = $url[1];
				} else {
					$_GET['action'] = $url[1];
				}
				if (isset($url[2])) {
					$_GET['id'] = $url[2];
				}
			}
		} else {
			$_GET['page'] = 'index';
		}

		/*парсинг строки*/
		/*поиск контролера*/
		if (isset($_GET['page'])) {
			$controllerName = ucfirst($_GET['page']) . 'Controller'; //IndexController
			//	echo ($controllerName)."<br>";
			$methodName = isset($_GET['action']) ? $_GET['action'] : 'index';
			$controller = new $controllerName();
			/*формирование контроллером данных для шаблона*/
			$data = [
				'content_data' => $controller->$methodName($_GET), //то что передает контроллер
				'title' => $controller->title,
				'pages' => Page::getPages(0), //хранит список страниц
				'categories' => Category::getParentCategories(), //хранит список категорий
				'auth' => Auth::isLoggedUser(), //только для автроризованные
				'user' => User::get('user_info'), //данные юзерал
				'admin' => Auth::isAdmin(), //проверка на админа
				'reviews' => Review::getReviews(), //получить все отзывы
				'visited_pages' => $_SESSION,
				'busket' => isset($_SESSION['cart']) ? $_SESSION['cart'] : [] //хранит строку с индексами товаров и кол-во
			];

			/*формирование контроллером данных для шаблона*/
			/*вытаскинвание из контроллера название шаблона и подгрузка twig*/
			$view = $controller->view . '/' . $methodName . '.html';
			if (!isset($_GET['asAjax'])) {
				$loader = new Twig_Loader_Filesystem(Config::get('path_templates'));

				// включил dump функцию
				$twig = new Twig_Environment($loader, ['debug' => true]);

				// создал свой фильтр
				$filter = new Twig_Filter('base64_encode', function ($input) {
					return base64_encode($input);
				});
				$twig->addFilter($filter);

				// добавил dump функцию в исключения
				$twig->addExtension(new Twig_Extension_Debug());
				$template = $twig->loadTemplate($view);

				/*вытаскинвание из контроллера название шаблона и подгрузка twig c отрисовкой страницы с посланными данными*/
				echo $template->render($data);
			} else {
				echo json_encode($data);
			}
		}
	}
}
