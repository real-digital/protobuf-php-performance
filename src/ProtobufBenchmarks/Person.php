<?php

namespace ProtobufBenchmarks;

use ProtobufBenchmarks\Proto;

class Person
{
	/**
	 * @var string
	 */
	public $name = "";

	/**
	 * @var int
	 */
	public $id = null;

	/**
	 * @var string
	 */
	public $email = "";

	/**
	 * @var []string
	 */
	public $phones = [];

	public function generateArray()
	{
		return [
			'name' => $this->name,
			'id' => $this->id,
			'email' => $this->email,
			'phones' => $this->phones,
		];
	}

	public function generateProto()
	{
		$message = new Proto\Person();
		$message->setName($this->name);
		$message->setId($this->id);
		$message->setEmail($this->email);

		$phones = [];
		foreach ($this->phones as $value) {
			$phoneType = $value['type'];
			$phoneNumber = $value['number'];
			$phone = new Proto\PhoneNumber();
			$phone->setNumber($phoneNumber);
			switch ($phoneType) {
				case 'home':
					$phone->setType(Proto\PhoneType::HOME);
					break;
				case 'work':
					$phone->setType(Proto\PhoneType::WORK);
					break;
				default:
					$phone->setType(Proto\PhoneType::MOBILE);
					break;
			}
			$phones[] = $phone;
		}

		$message->setPhone($phones);

		return $message;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	public function addPhone($type, $number)
	{
		$this->phones[] = ['type' => $type, 'number' => $number];

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getPhones()
	{
		return $this->phones;
	}
}
