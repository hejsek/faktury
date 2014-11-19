<?php

/**
 * @author Jakub Heyduk <heyduk2@seznam.cz>
 */

namespace App\Presenters;



use Nette,
	App\Model,
	\Nette\Application\UI\Form;

class LoginPresenter extends BasePresenter
{

	/**
	 * @var \App\Model\UserManager
	 * @inject
	 */

	public $userManager;

	/**
	 * @var Model\UserManager
	 * @inject
 	 */
	public $authenticator;


	public function checkRequirements($element)
	{
	}



	public function renderDefault()
	{
		if($this->user->isLoggedIn()) {
			$this->redirect("Identity:");
		}
	}



	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentFormLogin()
	{
		$form = new \Nette\Application\UI\Form;

		$form->addText('username', 'Login:')
			->addRule(FORM::EMAIL, "Přihlašovací údaje nejsou ve tvaru emailu.");

		$form->addPassword('password', 'Password:');

		$form->addSubmit('submit', 'Přihlásit');

		$form->onSuccess[] = callback($this, 'processFormLogin');
		return $form;
	}



	public function processFormLogin(Form $form)
	{
		$values = $form->getValues();

		$user = $this->getUser();

		$user->setAuthenticator($this->authenticator);

		try {
			$user->login($values->username, $values->password);
			$this->redirect('Identity:');
		} catch (\Nette\Security\AuthenticationException $e) {
			$this->flashMessage("Nesprávně zadané přihlašovací údaje.", "alert alert-danger");
		}
		$values = $form->getValues();
	}



	public function createComponentRegistrationForm()
	{
		$form = new Form();

		$form->addText("firstName")
			->setRequired();

		$form->addText("lastName")
			->setRequired();

		$form->addText("username")
			->addRule(FORM::EMAIL, "Musí být tvar emailu.")
			->setRequired();

		$form->addPassword("password")
			->setRequired();

		$form->addPassword("passwordCheck")
			->addRule(Form::EQUAL, "Hesla se neshodují.", $form["password"]);

		$form->addSubmit("submit");

		$form->onSuccess[] = callback($this, "processRegistrationForm");

		return $form;
	}



	public function processRegistrationForm(Form $form)
	{
		$values = $form->getValues();
		$values->role = "user";
		unset($values->passwordCheck);
		if ($this->userManager->userExist($values->username)) {
			$this->userManager->add($values);
			$this->flashMessage("Účet byl vytvořen.", "alert-success");
		} else {
			$this->flashMessage("Tento účet již existuje.", "alert alert-danger");
			return;
		}
	}
}