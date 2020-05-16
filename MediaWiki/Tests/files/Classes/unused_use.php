<?php
namespace FooBar;

use FooBar\Baz;
use InvalidArgumentException;
use Something\InAParam;
use Something\InAThrows;
use Something\InAVar;
use Something\InAVar2;
use Something\InAVar3;
use Something\InAVar4;
use Something\InAVar5;
use Something\InAVar6;
use Something\InAVar7;
use Something\InAVar8;
use Something\InAVar9;
use Something\OneTwo as ThreeFour;
use Something\Partial;
use Something\Something;
use Something\That\Is\Unused;
use Something\That\Is\Used;
use Something\UsedForPhanVar;
use Something\UsedForPhanVarComplex;
use Something\UsedForPhanVarForce;
use Used\But\Always\FullyQualified;
use Wikimedia\Database;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\LBFactory;

$a = new Baz();
$b = new Used();
$c = new ThreeFour();

class UnusedUseTest {
	/**
	 * @coversNothing
	 * @throws InAThrows
	 * @param InAParam $a A variable
	 * @return Database
	 */
	public function testDatabase( $a ) {
		return $a;
	}
}

class Foo {
	use SomeThing;
	use AnotherThing;

	/**
	 * @var InAVar
	 */
	private $thing;

	/**
	 * @var InAVar2|null
	 */
	private $thing2;

	/**
	 * @var null|InAVar3
	 */
	private $thing3;

	/**
	 * @var InAVar4[]
	 */
	private $thing4;

	/**
	 * @var (InAVar5|InAVar6)[]
	 */
	private $thing5;

	/**
	 * @var InAVar7<InAVar8,InAVar9>
	 */
	private $thing6;

	/**
	 * @var Partial\InAVar10|\Unused\NamespaceLooksLikeClass
	 */
	private $thing7;

	/**
	 * @var ILBFactory
	 */
	private $lbFactory;

	/**
	 * @var \Used\But\Always\FullyQualified
	 */
	private $thing8;

	/**
	 * @param ILBFactory $lbFactory
	 */
	public function __construct( ILBFactory $lbFactory ) {
		$this->lbFactory = $lbFactory;
		self::lbFactory();
	}

	/**
	 * @param array $arr
	 * @return int
	 */
	public function testPhanVar( $arr ) {
		'@phan-var UsedForPhanVar $exampleVar';
		'@phan-var InAVar7<InAVar8,UsedForPhanVarComplex> $exampleVar';
		$exampleVar = $arr[1];
		return $exampleVar->getNumber();
	}

	/**
	 * @param array $arr
	 * @return int
	 */
	public function testPhanVarForce( $arr ) {
		'@phan-var-force UsedForPhanVarForce $exampleVar';
		$exampleVar = $arr[1];
		return $exampleVar->getNumber();
	}
}

$fn = function () use ( $a ) {
	return $a->methodCall();
};
