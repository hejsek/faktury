<?php

namespace App\Presenters;



use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	public function checkRequirements($element)
	{
		if (!$this->user->loggedIn && $this->presenter->action != "activate" && $this->presenter->action != "resetPassword") {
			$this->redirect('Login:');
		}
	}



	public function handleLogout()
	{
		$this->user->logout();
		$this->redirect('Login:');
	}



	public function beforeRender()
	{
		if ($this->user->isLoggedIn()) {
			$this->template->username = $this->user->getIdentity()->username;
		}
	}
}
