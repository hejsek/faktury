<?php

namespace App\Model;



use Nette\ComponentModel\IContainer;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextArea;
use Nette\Forms\Controls\TextBase;
use Nette\Application\UI\Form;

/**
 * Wrapper for bootstrap3-rendering.php from examples (little bit improved)
 *
 * @author Tomáš Kolinger <tomas@kolinger.name> && Jakub Heyduk <heyduk2@seznam.cz>
 */
class BootstrapForm extends Form
{

	/**
	 * @param IContainer $parent
	 * @param string $name
	 */
	public function __construct(IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$renderer = $this->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';

		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

		$renderer->wrappers['error']['container'] = '';
		$renderer->wrappers['error']['item'] = 'div class="alert alert-danger"';

		$this->getElementPrototype()->role('form');
	}



	public function render()
	{
		foreach ($this->getControls() as $control) {

			if ($control instanceof Button && $control->getName() == "submit") {
				$control->setAttribute("class", "btn btn-primary");
			} elseif ($control instanceof Button) {
				$control->setAttribute('class', 'btn btn-default');
			} elseif ($control instanceof TextBase || $control instanceof SelectBox || $control instanceof MultiSelectBox || $control instanceof DateInput) {
				$control->setAttribute('class', 'form-control');
			} elseif ($control instanceof Checkbox || $control instanceof CheckboxList || $control instanceof RadioList) {
				$control->getSeparatorPrototype()->setName('div')->class($control->getControlPrototype()->type);
			}
			if ($control instanceof TextArea) {
				$control->setAttribute('data-ckeditor', 'true');
			}

			if ($control->getName() == "time_start" || $control->getName() == "time_end") {
				$control->setAttribute("class", "form-control datetimepicker");
			}
		}
		parent::render();
	}
}

