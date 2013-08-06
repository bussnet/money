<?php
/**
 * User: thorsten
 * Date: 05.08.13
 * Time: 16:57
 */

namespace Money;


interface CurrencyLookup {

	/**
	 * Return an Array with currencySettings
	 * @param $iso_code
	 * @return array
	 */
	public function getCurrencyByIsoCode($iso_code);

}

