<?php

/**
 * @author Jakub Heyduk <heyduk2@seznam.cz>
 */

namespace App\Presenters;



use Nette, h4kuna\Ares, Nette\Application\UI\Form, App\Model, App\Model\BootstrapForm;


class IdentityPresenter extends BasePresenter
{
	/**
	 * @var \App\Model\SubjectsModel
	 * @inject
	 */

	public $subjectsModel;


	public function renderDefault()
	{
		$this->template->subjects = $this->subjectsModel->getAllSubjects($this->user->identity->getId());
		$this->template->username = $this->user->identity->username;
	}


	public function createComponentFormAddSubjectAutomatically()
	{
		$form = new Form();

		$form->addText("ico")
			->addRule(FORM::INTEGER, "Toto není validní IČO.")
			->setRequired();

		$form->addSubmit("submit");

		$form->onSuccess[] = callback($this, "processFormAddSubjectAutomatically");

		return $form;
	}


	public function createComponentFormAddSubjectManually()
	{
		$form = new BootstrapForm();

		$form->addText("ico")
			->setAttribute("placeholder", "IČO")
			->addRule(FORM::INTEGER, "Toto není validní IČO.")
			->setRequired();

		$form->addText("tin")
			->setAttribute("placeholder", "DIČ")
			->setRequired();

		$form->addText("company")
			->setAttribute("placeholder", "Společnost")
			->setRequired();

		$form->addText("city")
		->setAttribute("placeholder", "Město");

		$form->addText("street")
			->setAttribute("placeholder", "Ulice");

		$form->addText("zip")
		->setAttribute("placeholder", "Poštovní směrovací číslo");

		$form->addText("country")
		->setAttribute("placeholder", "Země");

		$form->addText("court")
			->setAttribute("placeholder", "Soud")
			->setRequired();

		$form->addText("website")
			->setAttribute("placeholder", "Webové stránky")
			->addRule(FORM::URL, "Toto není validní URL.");

		$form->addText("email")
			->setAttribute("placeholder", "Emailová adresa")
			->addRule(FORM::EMAIL, "Toto není validní emailová adresa.");

		$form->addSubmit("submit");

		$form->onSuccess[] = callback($this, "processFormAddSubjectManually");

		return $form;
	}



	/**
	 * @param \App\Model\BootstrapForm $form
	 */
	public function processFormAddSubjectManually(BootstrapForm $form)
	{
		$values = $form->getValues();
		if($this->subjectsModel->subjectExists($values->ico)) {
			$this->flashMessage("Subjekt s zadaným IČO je již v systému.", "alert alert-danger");
				return;
		}

		$values["user"] = $this->user->identity->getId();

		$this->subjectsModel->addSubject($values);
	}


	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function processFormAddSubjectAutomatically(Form $form)
	{
		$values = $form->getValues();
		if($this->subjectsModel->subjectExists($values->ico)) {
			$this->flashMessage("Subjekt s zadaným IČO je již v systému.", "alert alert-danger");
				return;
		}
		$ares = new Ares();
		$data = $ares->loadData($values->ico);
		$data["ico"] = $values->ico;
		unset($data["in"]);
		$data["user"] = $this->user->identity->getId();

		$this->subjectsModel->addSubject($data);

	}
	
	public function handleDeleteSubject($id)
	{
		$this->subjectsModel->deleteSubject($id);
		$this->redirect("this");
	}
}