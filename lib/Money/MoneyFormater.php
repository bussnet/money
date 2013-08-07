<?php
/**
 * User: thorsten
 * Date: 07.08.13
 * Time: 15:31
 */

namespace Money;


/**
 * Class MoneyFormater for memory minimized usage in loops
 * @package Money
 */
class MoneyFormater {

	public function __construct($currency, $default_format_params=array()) {
		$this->money = new Money(0, Currency::getInstance($currency));
		$this->default_format_params = $default_format_params;
	}


	/**
	 * Format Amount with given currency
	 *
	 * @see Money::format()
	 * @param int $amount as subunit
	 * @param array $params params to format
	 * @return string
	 */
	public function format($amount, $params = array()) {
		return $this->money->setAmount($amount)->format($this->getParams($params));
	}

	/**
	 * return subunit amount legibly - make human readable
	 *
	 * @see Money::getAmount()
	 * @param int $amount as subunit
	 * @param array $params params for Money::getAmount()
	 * @return string
	 */
	public function legibly($amount, $params = array()) {
		return $this->money->setAmount($amount)->getAmount(true, $this->getParams($params));
	}

	/**
	 * merge defaultParams with given
	 * @param array $params
	 * @return array
	 */
	protected function getParams($params) {
		return array_merge($this->default_format_params, $params);
	}

}