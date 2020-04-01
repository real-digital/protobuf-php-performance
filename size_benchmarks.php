<?php

use ProtobufBenchmarks\Person;
use Symfony\Component\Yaml\Yaml;

require_once 'vendor/autoload.php';

runTest('default', defaultPerson());
runTest('long strings', personWithLongStrings());
runTest('a lot of phones', personWithALotOfPhones());



function runTest(string $title, Person $person)
{
	$data['protobuf--'] = $person->generateProto()->serializeToString();
	$data['json------'] = json_encode($person->generateArray());
	$data['yaml------'] = Yaml::dump($person->generateArray());
	$data['php-------'] = serialize($person);

	echo "Test $title" . PHP_EOL;
	foreach ($data as $protocol => $data) {
		$size = strlen($data) * 8;
		echo "{$protocol} {$size} bits";
		echo PHP_EOL;
	}

	echo PHP_EOL;
}

function defaultPerson()
{
	$person = (new Person())
		->setName("Max Mustermann")
		->setId(1)
		->setEmail("max@mustermann.com")
		->addPhone("home", "0123456789")
		->addPhone("mobile", "1234567890")
		->addPhone("work", "2345678901");

	return $person;
}

function personWithLongStrings()
{
	$person = (new Person())
		->setName(str_repeat("Max Mustermann", 100000))
		->setId(1)
		->setEmail(str_repeat("max@mustermann.com", 1000))
		->addPhone("home", "0123456789")
		->addPhone("mobile", "1234567890")
		->addPhone("work", "2345678901");

	return $person;
}

function personWithALotOfPhones()
{
	$person = (new Person())
		->setName("Max Mustermann")
		->setId(1)
		->setEmail("max@mustermann.com");

	for ($i = 0; $i < 10000; $i++) {
		$person
			->addPhone("home", (int)rand(10000000, 999999999))
			->addPhone("mobile", (int)rand(10000000, 999999999))
			->addPhone("work", (int)rand(10000000, 999999999));
	}

	return $person;
}
