<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	/**
	 * @var \App\Model\UserManager
	 * @inject
	 */
	public $userManager;

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

	public function renderActivate($hash)
	{
		$this->userManager->activateUser($hash);
		$this->flashMessage("Váš účet byl aktivován nyní se můžete přihlásit.", "alert alert-success");
		$this->redirect("Login:default");
	}

}
