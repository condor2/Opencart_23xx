<?php
namespace Cart;
class Currency {
	private $db;
	private $language;
	private $currencies = array();
	private $symbol_left_space = false;
	private $symbol_right_space = false;

	/**
	 * Constructor
	 *
	 * @param object $registry
	 */
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->language = $registry->get('language');
		$this->symbol_left_space = (bool)$registry->get('config')->get('config_symbol_left_space');
		$this->symbol_right_space = (bool)$registry->get('config')->get('config_symbol_right_space');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency`");

		foreach ($query->rows as $result) {
			$this->currencies[$result['code']] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'],
				'symbol_left'   => $result['symbol_left'],
				'symbol_right'  => $result['symbol_right'],
				'decimal_place' => $result['decimal_place'],
				'value'         => $result['value']
			);
		}
	}

	/**
	 * Format
	 *
	 * @param float  $number
	 * @param string $currency
	 * @param float  $value
	 * @param bool   $format
	 *
	 * @return string
	 */
	public function format($number, $currency, $value = 0, $format = true) {
		if (!isset($this->currencies[$currency])) {

			return '';
		}

		$symbol_left = $this->currencies[$currency]['symbol_left'];
		$symbol_right = $this->currencies[$currency]['symbol_right'];
		$decimal_place = $this->currencies[$currency]['decimal_place'];

		if (!$value) {
			$value = $this->currencies[$currency]['value'];
		}

		$amount = $value ? (float)$number * $value : (float)$number;

		$amount = round($amount, (int)$decimal_place);

		if (!$format) {
			return $amount;
		}

		$string = '';

		if ($symbol_left) {
			$string .= $symbol_left;

			if ($this->symbol_left_space) {
				$string .= ' ';
			}
		}

		$string .= number_format($amount, (int)$decimal_place, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

		if ($symbol_right) {
			$string .= $symbol_right;

			if ($this->symbol_right_space) {
				$string .= ' ';
			}
		}

		return $string;
	}

	/**
	 * Convert
	 *
	 * @param float  $value
	 * @param string $from
	 * @param string $to
	 *
	 * @return float
	 */
	public function convert($value, $from, $to) {
		if (isset($this->currencies[$from])) {
			$from = $this->currencies[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->currencies[$to])) {
			$to = $this->currencies[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}

	/**
	 * getId
	 *
	 * @param string $currency
	 *
	 * @return int
	 */
	public function getId($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
	}

	/**
	 * getSymbolLeft
	 *
	 * @param string $currency
	 *
	 * @return string
	 */
	public function getSymbolLeft($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
	}

	/**
	 * getSymbolRight
	 *
	 * @param string $currency
	 *
	 * @return string
	 */
	public function getSymbolRight($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
	}

	/**
	 * getDecimalPlace
	 *
	 * @param string $currency
	 *
	 * @return string
	 */
	public function getDecimalPlace($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
	}

	/**
	 * getValue
	 *
	 * @param string $currency
	 *
	 * @return float
	 */
	public function getValue($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
	}

	/**
	 * Has
	 *
	 * @param string $currency
	 *
	 * @return bool
	 */
	public function has($currency) {
		return isset($this->currencies[$currency]);
	}
}
