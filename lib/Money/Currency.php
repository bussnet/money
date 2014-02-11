<?php
/**
 * This file is part of the Money library
 *
 * Copyright (c) 2011-2013 Mathias Verraes
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Money;

class Currency
{
	/** @var  Currency[] */
	static $instances = array();

    /** @var string */
    private $iso_string;

    /** @var array */
    private static $currencies = array();

    /** @var string */
    private static $default_currency;

	/** @var CurrencyLookup */
	private static $currency_lookup;

	/** @var bool flag if currency has extended options*/
	private $is_extended_currency;

    /**
     * @param string $iso_string
     * @throws UnknownCurrencyException
     */
    public function __construct($iso_string)
    {
        if (!array_key_exists($iso_string, static::$currencies)) {
	        // get currency from LookupHelper
	        if (static::$currency_lookup instanceof CurrencyLookup)
		        static::$currencies[$iso_string] = static::$currency_lookup->getCurrencyByIsoCode($iso_string);
			elseif (empty(static::$currencies)) // if empty, load from original php-list
				static::$currencies = require __DIR__ . '/currencies.php';
	        else
	            throw new UnknownCurrencyException($iso_string);
        }
        $this->iso_string = $iso_string;
        $this->is_extended_currency = is_array(static::$currencies[$iso_string]);


	    // set currency data as obj data
	    if (is_array(static::$currencies[$iso_string])) {
		    foreach (static::$currencies[$iso_string] as $k => $v) {
			    $this->$k = $v;
		    }
	    }
    }

	/**
	 * return instance of MOneyObj - for single use to reduce memory usage in loops
	 * @param string|Currency $currency
	 * @return Currency
	 */
	public static function getInstance($currency = null) {
		if ($currency instanceof Currency)
			return $currency;
		// get isocode from currency, direct or default
		$iso_code = $currency ?: Currency::getDefaultCurrency();
		if (!array_key_exists($iso_code, static::$instances)) {
			static::$instances[$iso_code] = new static($iso_code);
		}
		return static::$instances[$iso_code];
	}

	/**
     * @return string
     */
    public function getIsostring()
    {
        return $this->iso_string;
    }

    /**
     * @param \Money\Currency $other
     * @return bool
     */
    public function equals(Currency $other)
    {
        return $this->iso_string === $other->iso_string;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getIsostring();
    }

	/**
	 * @param array $default_currency
	 * @return $this
	 */
	public static function setDefaultCurrency($default_currency) {
		self::$default_currency = $default_currency;
	}

	/**
	 * @return string
	 */
	public static function getDefaultCurrency() {
		return self::$default_currency;
	}

	/**
	 * @param \Money\CurrencyLookup $currency_lookup
	 * @return $this
	 */
	public static function setCurrencyLookup(CurrencyLookup $currency_lookup) {
		self::$currency_lookup = $currency_lookup;
	}

	/**
	 * @return \Money\CurrencyLookup
	 */
	public static function getCurrencyLookup() {
		return self::$currency_lookup;
	}

	/**
	 * assert that this currency is extended
	 * @throws CurrencyIsNoExtendedCurrencyException
	 */
	protected function assertExtendedCurrency() {
		if (!$this->is_extended_currency)
			throw new CurrencyIsNoExtendedCurrencyException(sprintf('currency %s is no extended currency, which is need for this method. Add CurrencyLookup', $this->getIsostring()));
	}

	/**
	 * return currency symbol or html_entity
	 * @param bool $asHtml
	 * @return mixed
	 */
	public function getSymbol($asHtml=false) {
		return $asHtml
			? $this->getHtmlEntity()
			: $this->get('symbol');
	}

	/**
	 * return is_symbol_before amount
	 * @return bool
	 */
	public function getSymbolFirst() {
		return !!$this->get('symbol_first');
	}

	/**
	 * return string|int if symbol are before or after the amount
	 * @param bool $asString
	 * @return int|string
	 */
	public function getSymbolPosition($asString=false) {
		$position = $this->getSymbolFirst() ? -1 : 1;
		return $asString
			? ($position>0?'after':'before')
			: $position;
	}

	/**
	 * return the decimal mark (splitter\sign) for currency
	 * @return string
	 */
	public function getDecimalMark() {
		return $this->get('decimal_mark');
	}

	/**
	 * return the thousandsseparator (splitter|sign) for currency
	 * @return string
	 */
	public function getThousandsSeparator() {
		return $this->get('thousands_separator');
	}

	/**
	 * return the amount of decimal places for this currency
	 * @return float|int
	 */
	public function getDecimalPlaces() {
		if ($this->getSubunitToUnit() == 1)
			return 0;
		elseif ($this->getSubunitToUnit() % 10 == 0)
			return floor(log10($this->getSubunitToUnit()));
		else
			return floor(log10($this->getSubunitToUnit()) + 1);
	}

	/**
	 * return the factor between unit/subunit
	 * @return int
	 */
	public function getSubunitToUnit() {
		$this->assertExtendedCurrency();
		return (int)$this->get('subunit_to_unit');
	}

	/**
	 * return the html_entity of the currency symbol
	 * @return string
	 */
	private function getHtmlEntity() {
		return $this->get('html_entity');
	}

	/**
	 * return the numeric iso code
	 * @return int
	 */
	public function getIsoNumeric() {
		return (int)$this->get('iso_numeric');
	}

	/**
	 * return the name of the currency
	 * @return string
	 */
	public function getName() {
		return $this->get('name');
	}

	/**
	 * return the name of the subunit
	 * @return string
	 */
	public function getSubunit() {
		return $this->get('subunit');
	}

	/**
	 * return list of alternate symbols - maybe empty
	 * @return array
	 */
	public function getAlternateSymbols() {
		return $this->get('alternate_symbols');
	}

	/**
	 * return a extended currencyvalue if currency is extended and value exists
	 * @param $string
	 * @return mixed
	 */
	protected function get($key) {
		$this->assertExtendedCurrency();
		if (!isset($this->$key))
			throw new UnknownCurrencyExtendedValueException(sprintf('the extended value %s is not set in currency %s', $key, $this->getIsostring()));
		return $this->$key;
	}

	/**
	 * Format Amount with given currency
	 *
	 * @see Money::format()
	 * @see Money::getInstance()
	 * @param int $amount as subunit
	 * @param array $params params to format
	 * @return string
	 */
	public function format($amount, $params = array()) {
		return Money::getInstance($this)->setAmount($amount)->format($params);
	}

	/**
	 * return subunit amount legibly - make human readable
	 *
	 * @see Money::getAmount()
	 * @see Money::getInstance()
	 * @param int $amount as subunit
	 * @param array $params params for Money::getAmount()
	 * @return string
	 */
	public function legibly($amount, $params = array()) {
		return Money::getInstance($this)->setAmount($amount)->getAmount(true, $params);
	}

}
// set the defaultCurrency - EUR if DEFAULT_CURRENCY const undefined
Currency::setDefaultCurrency(@constant('DEFAULT_CURRENCY') ?: 'EUR');