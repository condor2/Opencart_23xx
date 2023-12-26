<?php
namespace Cart;
class Affiliate {
	private object $db;
	private object $config;
	private object $request;
	private object $session;
	private int $affiliate_id = 0;
	private string $firstname = '';
	private string $lastname = '';
	private string $email = '';
	private string $telephone = '';
	private string $fax = '';
	private string $code = '';

	public function __construct(object $registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['affiliate_id'])) {
			$affiliate_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$this->session->data['affiliate_id'] . "' AND status = '1'");

			if ($affiliate_query->num_rows) {
				$this->affiliate_id = $affiliate_query->row['affiliate_id'];
				$this->firstname = $affiliate_query->row['firstname'];
				$this->lastname = $affiliate_query->row['lastname'];
				$this->email = $affiliate_query->row['email'];
				$this->telephone = $affiliate_query->row['telephone'];
				$this->fax = $affiliate_query->row['fax'];
				$this->code = $affiliate_query->row['code'];

				$this->db->query("UPDATE " . DB_PREFIX . "affiliate SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE affiliate_id = '" . (int)$this->session->data['affiliate_id'] . "'");
			} else {
				$this->logout();
			}
		}
	}

	public function login(string $email, string $password): bool {
		$affiliate_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1' AND approved = '1'");

		if ($affiliate_query->num_rows) {
			if (password_verify($password, $affiliate_query->row['password'])) {
				$rehash = password_needs_rehash($affiliate_query->row['password'], PASSWORD_DEFAULT);
			} elseif (isset($affiliate_query->row['salt']) && $affiliate_query->row['password'] == sha1($affiliate_query->row['salt'] . sha1($affiliate_query->row['salt'] . sha1($password)))) {
				$rehash = true;
			} elseif ($affiliate_query->row['password'] == md5($password)) {
				$rehash = true;
			} else {
				return false;
			}

			if ($rehash) {
				$this->db->query("UPDATE `" . DB_PREFIX . "affiliate` SET `password` = '" . $this->db->escape(password_hash($password, PASSWORD_DEFAULT)) . "' WHERE `affiliate_id` = '" . (int)$affiliate_query->row['affiliate_id'] . "'");
			}

			$this->session->data['affiliate_id'] = $affiliate_query->row['affiliate_id'];

			$this->affiliate_id = $affiliate_query->row['affiliate_id'];
			$this->firstname = $affiliate_query->row['firstname'];
			$this->lastname = $affiliate_query->row['lastname'];
			$this->email = $affiliate_query->row['email'];
			$this->telephone = $affiliate_query->row['telephone'];
			$this->fax = $affiliate_query->row['fax'];
			$this->code = $affiliate_query->row['code'];

			return true;
		} else {
			return false;
		}
	}

	public function logout(): void {
		unset($this->session->data['affiliate_id']);

		$this->affiliate_id = 0;
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
	}

	public function isLogged(): bool {
		return $this->affiliate_id;
	}

	public function getId(): int {
		return $this->affiliate_id;
	}

	public function getFirstName(): string {
		return $this->firstname;
	}

	public function getLastName(): string {
		return $this->lastname;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getTelephone(): string {
		return $this->telephone;
	}

	public function getFax(): string {
		return $this->fax;
	}

	public function getCode(): string {
		return $this->code;
	}
}
