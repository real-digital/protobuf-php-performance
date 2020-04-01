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
class EncodeBench
{
    /**
     * @var \ProtobufBenchmarks\Person
     */
    protected $person = null;

    protected $personProto = null;

    public function classSetUp()
    {
        $this->person = (new Person())
            ->setName("Christopher Mancini")
            ->setId(1)
            ->setEmail("chris@mydomain.com")
            ->addPhone("home", "0123456789")
            ->addPhone("mobile", "1234567890")
            ->addPhone("work", "2345678901");

        $this->personProto = $this->person->generateProto();
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function encodeProtobuf()
    {
        $data = $this->personProto->serializeToString();
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function encodeJson()
    {
		$data = json_encode($this->person->generateArray());
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function encodeYaml()
    {
		$data = Yaml::dump($this->person->generateArray());
    }

	/**
	 * @Subject()
	 * @Revs(10000)
	 * @Iterations(5)
	 */
    public function encodePhp()
    {
		$data = serialize($this->person);
    }
}
