<?php
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
class FailedExamples {
	/**
	 * @param string $foo
	 * @param ?MyClass $x
	 */
	public function docNullableArgOptional( $foo, MyClass $x = null ) {
	}

	/**
	 * @param string $foo
	 * @param MyClass|null $x
	 */
	public function nullableAfterRequired( $foo, MyClass $x = null ) {
	}

	/**
	 * @param string $foo
	 * @param MyClass|null $x
	 * @param MyClass|null $y
	 */
	public function nullableAfterRequired2( $foo, MyClass $x = null, MyClass $y = null ) {
	}

	/**
	 * @param MyClass|null $x
	 */
	public function nullableOnly( MyClass $x = null ) {
	}
}

class PassedExamples {
	/**
	 * @param ?MyClass $x
	 * @param string $foo
	 */
	public function genericNullable( ?MyClass $x, $foo ) {
	}

	/**
	 * We allow this per T218816.
	 * @param string $foo
	 * @param MyClass|null $x
	 */
	public function docOptionalArgNullable( $foo, ?MyClass $x ) {
	}
}
