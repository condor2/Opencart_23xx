<?php
abstract class Controller {
	protected $registry;

	public function __construct(object $registry) {
		$this->registry = $registry;
	}

	public function __get(string $key): object {
		return $this->registry->get($key);
	}

	public function __set(string $key, object $value): void {
		$this->registry->set($key, $value);
	}
}