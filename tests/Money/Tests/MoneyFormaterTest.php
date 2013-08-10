<?php
/**
 * User: thorsten
 * Date: 07.08.13
 * Time: 15:48
 */


namespace Money\Tests;

require_once('MoneyTestCase.php');

use Money\MoneyFormater;

class MoneyFormaterTest extends MoneyTestCase {

	public function testFormater() {
		$m = new MoneyFormater('USD');
		$this->assertEquals('$1,234.56', $m->format(123456));
		$this->assertEquals('$1,234', $m->format(123456, array('no_cents' => true)));

		// with defaultParams
		$m = new MoneyFormater('USD', array('symbol' => '~'));
		$this->assertEquals('~1,234.56', $m->format(123456));
		$this->assertEquals('~1,234', $m->format(123456, array('no_cents' => true)));
	}

	public function testLegibly() {
		$m = new MoneyFormater('EUR');
		$this->assertEquals('1234,56', $m->legibly(123456));
		$this->assertEquals('1234', $m->legibly(123400));
		$this->assertEquals('1234,00', $m->legibly(123400, array('force_decimal' => true)));

		// with defaultParams
		$m = new MoneyFormater('EUR', array('symbol' => '~'));
		$this->assertEquals('1234,56', $m->legibly(123456));
		$this->assertEquals('1234,00', $m->legibly(123400, array('force_decimal' => true)));
		$this->assertEquals('1234', $m->legibly(123400));
	}
}
