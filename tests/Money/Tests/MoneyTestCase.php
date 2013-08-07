<?php
/**
 * User: thorsten
 * Date: 07.08.13
 * Time: 16:48
 */

namespace Money\Tests;


use Money\Currency;
use Money\CurrencyLookupRubyMoney;

class MoneyTestCase extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		parent::setUp();
		// load RubyMoney CurrencyList and register lookup
		Currency::setCurrencyLookup(new CurrencyLookupRubyMoney());
	}

}