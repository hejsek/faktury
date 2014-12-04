<?php

namespace App\Model;



use Nette,
	Nette\Utils\Strings,
	Nette\Security\Passwords,
	Nette\Mail\Message,
	Nette\Mail\SendmailMailer;


/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{

	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role';


	/** @var Nette\Database\Context */
	private $database;



	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}



	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update(array(
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			));
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}



	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($values, $basePath)
	{
		//		$this->database->table(self::TABLE_NAME)->insert(array(
		//			self::COLUMN_NAME => $username,
		//			self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
		//		));
		$values->password = Passwords::hash($values->password);
		$values->hash = Strings::random();
		$values->confirmed = FALSE;

		$this->database->query("INSERT INTO users ?", $values);

		$mail = new Message;
		$mail->setFrom('Aktivace <root@local.net>')
			->addTo($values->username)
			->setSubject('Potvrzení registrace')
			->setHtmlBody("<html><body><a target='_blank' href='http://" . $basePath . "activate/" . $values->hash . "'>Aktivace účtu</a></body>");


		$mailer = new SendmailMailer;
		$mailer->send($mail);
	}



	public function activateUser($hash)
	{
		$this->database->query("UPDATE users SET confirmed = 1 WHERE hash = ?", $hash);
	}



	public function userIsActivated($username)
	{
		$res = $this->database
			->table("users")
			->select("confirmed")
			->where("username", $username)
			->fetch()["confirmed"];

		if ($res == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	public function userExist($username)
	{
		$result = $this->database
			->table("users")
			->where("username", $username)
			->fetch();

		//todo
		if (!$result) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	public function test()
	{
		// dump($this->hashExists("root@local.net"));
		/*$basePath = "127.0.0.1/faktury/sandbox/www/";
		$hash = Strings::random();

			$mail = new Message;
			$mail->setFrom('Aktivace <root@local.net>')
				->addTo("root@local.net")
				->setSubject('Potvrzení registrace')
				->setHtmlBody("<html><body><a target='_blank' href='http://".$basePath."activate/" . $hash."'>Aktivace účtu</a></body>");
//				->setHtmlBody("http://seznam.cz");


			$mailer = new SendmailMailer;
			$mailer->send($mail);*/
	}



	public function sendNewPasswordLink($username)
	{
		$basePath = "127.0.0.1/faktury/sandbox/www/";
		$hash = Strings::random();

		$mail = new Message;
		$mail->setFrom('Info <root@local.net>')
			->addTo($username)
			->setSubject('Změna hesla')
			->setHtmlBody("<html><body><a target='_blank' href='http://" . $basePath . "resetPassword/" . $hash . "'>Odkaz na změnu hesla.</a></body>");
		//				->setHtmlBody("http://seznam.cz");


		$mailer = new SendmailMailer;
		$mailer->send($mail);

		$this->database->query('UPDATE users SET hash = ?, confirmed = 0 WHERE username = ?', $hash, $username);
	}

	public function setNewPassword($password, $hash)
	{
		$password = Passwords::hash($password);

		$this->database->query("UPDATE users SET password = ?, confirmed = 1 WHERE hash = ?", $password, $hash);
	}

	public function getUsernameByHash($hash)
	{
		return $this->database
			->table("users")
			->select("username")
			->where("hash", $hash)
			->fetch()["username"];
	}
}
