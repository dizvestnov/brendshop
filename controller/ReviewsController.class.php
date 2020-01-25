<?php

class ReviewsController extends Controller
{
	public $view = 'reviews';

	// выводим список (БД)
	function index($data)
	{
		Auth::onlyAuth('/?path=auth');
		$items = Review::getReviews();
		$this->title = 'Reviews';

		$error = false;
		if (isset($_POST['add_comment'])) {
			$id_user = $_SESSION['auth']['id'];
			$content = htmlspecialchars($_POST['content']);
			Review::createReview($id_user, $content);
			if (!empty($content)) {
				header("Location: /?path=reviews");
			} else {
				$error = 'Что-то пошло не так!';
			}
		}
		return [
			'error' => $error,
			'items' => $items,
			'user' => User::get('user_info'),
		];
	}

	// удалить (GET button)
	function delete()
	{
		if (!Auth::isAdmin() && !Auth::isLoggedUser()) {
			header("Location: /?path=reviews");
		} else {
			$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;

			if ($id) {
				Review::deleteReview($id);
			}

			header("Location: /?path=reviews");
		}
	}

	// редактировать (GET button)
	function edit()
	{
		if (!Auth::isAdmin() && !Auth::isLoggedUser()) {
			header("Location: /?path=reviews");
		} else {
			$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
			$content = (isset($_GET['id']));
			if ($id) {
				Review::updateReview($id, $content);
			}
			header("Location: /?path=reviews");
		}
	}
}
