<?php

/**
 * @author Jakub Heyduk <heyduk2@seznam.cz>
 */

namespace App\Model;

class SubjectsModel extends BaseModel
{

	/**
	 * @param $values
	 */
	public function addSubject($values) {
		$this->getContext()->query("INSERT INTO subjects ?", $values);
	}



	/**
	 * @param $user
	 * @return \Nette\Database\Table\Selection
	 */
	public function getAllSubjects($user)
	{
		return $this->getContext()
			->table("subjects")
			->select("*")
			->where("user", $user);
	}



	/**
	 * @param $ico
	 * @param $user
	 * @return bool
	 */
	public function subjectExists($ico, $user)
	{
		$result = $this->getContext()
			->table("subjects")
			->select("*")
			->where("ico", $ico)
			->where("user", $user)
			->fetch();

		if($result) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	/**
	 * @param $id
	 */
	public function deleteSubject($id)
	{
		$this->getContext()->query("DELETE FROM subjects WHERE id = ?", $id);
	}



	/**
	 * @param $id
	 * @return bool|mixed|IRow
	 */
	public function getSubject($id)
	{
		return $this->getContext()
			->table("subjects")
			->where("id", $id)
			->fetch();
	}
}