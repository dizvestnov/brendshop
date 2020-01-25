<?php

class UserController extends Controller
{
	public $view = 'user';

	public function index($data)
	{
		Auth::onlyAuth('/?path=auth');
		$this->title = 'Account';
		Order::set('id_user', User::get('user_info')['id_user']);

		return [
			'user' => User::get('user_info'),
			'orders' => ((Order::getUserOrders()) ? Order::getUserOrders() : 'No orders yet :(')
		];
	}
}
