<?php

/**
 * @author Jakub Heyduk <heyduk2@seznam.cz>
 */

namespace App\Model;

class SubjectsModel extends BaseModel
{
	public function addSubject($values) {
		$this->getContext()->query("INSERT INTO subjects ?", $values);
	}

	public function getAllSubjects($user)
	{
		return $this->getContext()
			->table("subjects")
			->select("*")
			->where("user", $user);
	}
	
	public function subjectExists($ico)
	{
		$result = $this->getContext()
			->table("subjects")
			->select("*")
			->where("ico", $ico)
			->fetch();

		if($result) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteSubject($id)
	{
		$this->getContext()->query("DELETE FROM subjects WHERE id = ?", $id);
	}
}