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

	private $username;

	private $hash;



	public function checkRequirements($element)
	{
	}



	public function renderDefault()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect("Identity:");
		}
	}



	public function renderResetPassword($username)
	{
		$this->username = $username;
		$this->hash = $this->getParameter("hash");
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
		if (!$this->userManager->userIsActivated($values->username)) {
			$this->flashMessage("Musíte mít aktivovaný účet.", "alert alert-danger");
			return;
		}

		$user->setAuthenticator($this->authenticator);

		try {
			$user->login($values->username, $values->password);
			$this->redirect('Identity:');
		} catch (\Nette\Security\AuthenticationException $e) {
			$this->flashMessage("Nesprávně zadané přihlašovací údaje.", "alert alert-danger");
		}
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
			$http = $this->getHttpRequest();

			$this->userManager->add($values, $http->getRemoteAddress() . $http->url->scriptPath);
			$this->flashMessage("Účet byl vytvořen na email vám byl odeslán aktivační email.", "alert alert-warning");
			$this->redirect("default");
		} else {
			$this->flashMessage("Tento účet již existuje.", "alert alert-danger");
			return;
		}
	}



	public function createComponentFormResetPassword()
	{
		$form = new \App\Model\BootstrapForm();


		$form->addText("username")
			->addRule(FORM::EMAIL, "Email nemá správný formát.");

		$form->addSubmit("submit", "Odeslat email");

		$form->onSuccess[] = callback($this, "processResetPasswordForm");

		return $form;
	}



	public function processResetPasswordForm(Form $form)
	{
		$values = $form->getValues();

		if (!$this->userManager->userExist($values->username)) {
			$this->userManager->sendNewPasswordLink($values->username);
			$this->flashMessage("Na zadaný email vám byl odeslán odkaz na reset hesla", "alert alert-info");
		} else {
			$this->flashMessage("Uživatel nebyl nalezen.", "alert alert-danger");
		}
	}



	public function createComponentFormSetNewPassword()
	{
		$form = new Form();


		$form->addPassword("password")
			->setRequired();

		$form->addPassword("passwordCheck")
			->addRule(Form::EQUAL, "Hesla se neshodují.", $form["password"]);

		$form->addHidden("hash")
			->setValue($this->getParameter("hash"));

		$form->addSubmit("submit", "Změnit heslo");

		$form->onSuccess[] = callback($this, "processSetNewPasswordForm");

		return $form;
	}



	public function processSetNewPasswordForm(Form $form)
	{
		$values = $form->getValues();

		$this->userManager->setNewPassword($values->password, $values->hash);
		//přidat metodu co sem dá username podle hashe
		$this->user->login($this->userManager->getUsernameByHash($values->hash), $values->password);
		$this->flashMessage("Vaše heslo bylo úspěšně změněno", "alert alert-success");
		$this->redirect("Identity:");
	}
}