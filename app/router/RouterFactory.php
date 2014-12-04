<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();

		$router[] = new Route('activate/<hash>', 'Homepage:activate');
		$router[] = new Route('resetPassword/<hash>', 'Login:resetPassword');

		$router[] = new Route('<presenter>/<action>[/<id>]', 'Identity:default');

		return $router;
	}

}
