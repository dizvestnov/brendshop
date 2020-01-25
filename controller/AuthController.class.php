<?php

class AuthController extends Controller
{
	public $view = 'auth';

	public function index($data)
	{
		if (Auth::isLoggedUser()) header("Location: /?path=user");
		$this->title = 'Authorization';
		// проверка данных из формы
		if (isset($_POST['login_user'])) {
			Auth::loginUser();
		}

		// грузим из POST
		if (isset($_POST['reg_user'])) {
			Auth::regUser();
		}

		return [];
	}

	// Форма авторизации
	function sign_in($data)
	{
		if (!isset($_GET['popup'])) Auth::onlyAuth('/?path=auth');
			$this->title = 'Sign In';
	
			return [];
		// header("Location: /?path=auth");
		
	}

	// Форма регистрации
	function sign_up($data)
	{
		if (isset($_GET['popup'])) {
			$this->title = 'Sign Up';
	
			return [];
		}
		// header("Location: /?path=auth");
		Auth::onlyAuth('/?path=auth');
	}

	function logout()
	{
		Auth::logoutUser();
	}
}
