<?php

class MySQLActiveUser
{
	public
		$cookie_domain = '',
		$cookie_path = '/',
		$active_user_id,
		$active_user_name,
		$active_user_password,
		$active_user_email,
		$active_real_name,
		$active_user_login_time;

	protected
		$_cookie_name = 'wiki_user';


	public function set_cookie($username, $user_id, $password_hash, $user_email, $real_name, $login_time) {
		$this->_set_cookie($username, $user_id, $user_email, $real_name, $login_time);

		$this->active_user_name = $username;
		$this->active_user_id = $user_id;
		$this->active_user_password = $password_hash;
		$this->active_user_email = $user_email;
		$this->active_real_name = $real_name;
		$this->active_user_login_time = $login_time;
	}

	public function clear_cookie() {
		$this->active_user_name = "";
		$this->active_user_id = 0;
		$this->active_user_password = 0;
		$this->active_user_email = "";
		$this->active_real_name = "";
		$this->active_user_login_time = 0;
		$this->_delete_cookie();
	}

	public function distribute_cookie_data() {
		if ( ($cookie = $this->_get_cookie()) ) {
			list($this->active_user_name,
				$this->active_user_id,
				$this->active_user_email,
				$this->active_real_name,
				$this->active_user_login_time) = $cookie;
		}
	}


	protected function _set_cookie($username, $user_id, $user_email, $real_name, $login_time)
	{
		$data = gzdeflate(serialize(array(
			$username, $user_id, $user_email, $real_name, $login_time
		)));
		$r = setcookie($this->_cookie_name, $data, time()+60*60*24*100, $this->cookie_path, $this->cookie_domain);
	}

	protected function _get_cookie()
	{
		if ( isset($_COOKIE[$this->_cookie_name]) ) {
			return unserialize( gzinflate($_COOKIE[$this->_cookie_name]) );
		}

		return null;
	}

	protected function _delete_cookie()
	{
		setcookie($this->_cookie_name, '', time()-60*60*24, $this->cookie_path, $this->cookie_domain);
	}
}

/**
*
* The MySQL cache epoche timer is for when to rebuild the cache stored on the client side.
* This is ussually done at login.
*
*/
function CacheTimer_viaMySQL() {
	global $MySQLActiveUserData;
	return $MySQLActiveUserData->active_user_login_time;
}

$GLOBALS['MySQLActiveUserData'] = new MySQLActiveUser();
