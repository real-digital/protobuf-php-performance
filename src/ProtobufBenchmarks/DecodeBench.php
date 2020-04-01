<?php

namespace ProtobufBenchmarks;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Subject;
use Symfony\Component\Yaml\Yaml;

/**
 * @BeforeMethods({"classSetUp"})
 */
class DecodeBench
{
    /**
     * @var \ProtobufBenchmarks\Person
     */
    protected $person = null;

	protected $personProto = null;

	protected $data = [
        'protobuf' => null,
        'json' => null,
        'yaml' => null,
        'php' => null,
    ];

    public function classSetUp()
    {
        $this->person = (new Person())
            ->setName("Max Mustermann")
            ->setId(1)
            ->setEmail("max@mustermann.com")
            ->addPhone("home", "0123456789")
            ->addPhone("mobile", "1234567890")
            ->addPhone("work", "2345678901");

		$this->personProto = $this->person->generateProto();

		$this->data['protobuf'] = $this->personProto->serializeToString();

		$this->data['json'] = json_encode($this->person->generateArray());

		$this->data['yaml'] = Yaml::dump($this->person->generateArray());

		$this->data['php'] = serialize($this->person);
    }

    /**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
     */
    public function decodeProtobuf()
    {
        $data = new Proto\Person();
        $data->mergeFromString($this->data['protobuf']);
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function decodeJson()
    {
        $data = json_decode($this->data['json']);
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function decodeYaml()
    {
        $data = Yaml::parse($this->data['yaml']);
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function decodePhp()
    {
        $data = unserialize($this->data['php']);
    }
}
