<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 04/05/2017
 * Time: 14:25
 */
class UserVO
{
	private $ur1identifier; // code apogee ou harpege
	private $enssatPrimaryKey; // 32 bits
	private $username;
	private $name;
	private $surname;
	private $phone;
	private $status; // etudiant, exterieur, personnel
	private $email;

	// GETTER
	public function getUr1identifier() {
		return $this->ur1identifier;
	}

	public function getEnssatPrimaryKey() {
		return $this->enssatPrimaryKey;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getName() {
		return $this->name;
	}

	public function getSurname() {
		return $this->surname;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getEmail() {
		return $this->email;
	}

	// SETTER
	public function setUr1identifier($ur1identifier) {
		$this->ur1identifier = $ur1identifier;
	}

	public function setEnssatPrimaryKey($id) {
		$this->enssatPrimaryKey = $id;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setSurname($surname) {
		$this->surname = $surname;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

}