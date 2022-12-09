<?php
namespace Cart;
class User {
	private int $user_id = 0;
	private string $username = '';
	private array $permission = array();
	private $db;
	private $request;
	private $session;

	/**
	 * Constructor
	 *
	 * @param    object  $registry
	 */
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['user_group_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = json_decode($user_group_query->row['permission'], true);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	/**
	 * Login
	 *
	 * @param    string  $username
	 * @param    string  $password
	 *
	 * @return   bool
	 */
	public function login(string $username, string $password): bool {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape(htmlspecialchars($password, ENT_QUOTES)) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];

			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = json_decode($user_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Logout
	 *
	  * @return   void
	 */
	public function logout(): void {
		unset($this->session->data['user_id']);

		$this->user_id = 0;
		$this->username = '';
	}

	/**
	 * hasPermission
	 *
	 * @param    string  $key
	 * @param    mixed  $value
	 *
	 * @return   bool
	 */
	public function hasPermission(string $key, mixed $value): bool {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	/**
	 * isLogged
	 *
	 * @return   bool
	 */
	public function isLogged(): bool {
		return $this->user_id ? true : false;
	}

	/**
	 * getId
	 *
	 * @return   int
	 */
	public function getId(): int {
		return $this->user_id;
	}

	/**
	 * getUserName
	 *
	 * @return   string
	 */
	public function getUserName(): string {
		return $this->username;
	}

	/**
	 * getGroupId
	 *
	 * @return   int
	 */
	public function getGroupId(): int {
		return $this->user_group_id;
	}
}
