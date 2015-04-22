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

		// try and read from cache
		$cache_file = $cache_dir . '/' . basename($file);
		if (file_exists($cache_file)) {
			$content = file_get_contents($cache_file);
			if ($content) {
				$this->currencies = json_decode($content, true);
				if ($this->currencies) {
					return;
				}
			}
		}

		// try and read from server
		$content = file_get_contents($file);
		if (!$content) {
			throw new \Exception('Could not read currencies file from cache or server.');
		}
		$this->currencies = json_decode($content, true);
		if (!$this->currencies) {
			throw new \Exception('Could decode currencies file from server.');
		}

		// write to cache
		if ($cache_dir && is_dir($cache_dir)) {
			file_put_contents($cache_file, $content);
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
