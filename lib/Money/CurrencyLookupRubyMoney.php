<?php
/**
 * User: thorsten
 * Date: 06.08.13
 * Time: 11:01
 */

namespace Money;

/**
 * Class CurrencyLookupRubyMoney
 * download a list of currencies with extended values and make it avialibe via CurrencyLookup Interface
 * @package Money
 */
class CurrencyLookupRubyMoney implements CurrencyLookup {

	const CURRENCY_FILE = 'https://raw.github.com/RubyMoney/money/master/config/currency_iso.json';

	/**
	 * @var array
	 */
	public $currencies;

	/**
	 * Load currency-json file and parse
	 * @param null $file
	 * @param string $cache_dir
	 */
	function __construct($file=null, $cache_dir='/tmp/') {
		$file = $file ? : self::CURRENCY_FILE;

		// read from cache
		if (file_exists($cache_dir.'/'.basename($file)))
			$file = $cache_dir . '/' . basename($file);

		$this->currencies = json_decode(file_get_contents($file), true);

		// write to cache
		if ($cache_dir && file_exists($cache_dir)) {
			file_put_contents($cache_dir . '/' . basename($file), file_get_contents($file));
		}
	}

	/**
	 * Return an Array with currencySettings
	 * @param $iso_code
	 * @return array
	 */
	public function getCurrencyByIsoCode($iso_code) {
		if (array_key_exists(strtolower($iso_code), $this->currencies))
			return $this->currencies[strtolower($iso_code)];
		throw new UnknownCurrencyException(sprintf('currency with iso-code %s not found', $iso_code));
	}

}