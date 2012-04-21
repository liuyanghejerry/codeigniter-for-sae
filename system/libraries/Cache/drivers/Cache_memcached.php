<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2011 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource	
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Memcached Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */

class CI_Cache_memcached extends CI_Driver {

	private $_memcached;	// Due to SAE, it's just a return value of memcache_init(), usually TRUE/FALSE, NOT A OBJECT

	protected $_memcache_conf 	= array(
					'default' => array(
						'default_host'		=> '127.0.0.1',
						'default_port'		=> 11211,
						'default_weight'	=> 1
					)
				);

	// ------------------------------------------------------------------------	

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */	
	public function get($id)
	{	
		$data = memcache_get($this->_memcached, $id);
		
		$CI =& get_instance();
		if ($CI->config->item('sae_memcache') == FALSE)
		{
			log_message('error','You\'ve closed SAE Memcache but was trying to use it.');
		}
		return (is_array($data)) ? $data[0] : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Save
	 *
	 * @param 	string		unique identifier
	 * @param 	mixed		data being cached
	 * @param 	int			time to live
	 * @return 	boolean 	true on success, false on failure
	 */
	public function save($id, $data, $ttl = 60)
	{
		//return $this->_memcached->set($id, array($data, time(), $ttl), 0, $ttl);
		$CI =& get_instance();
		if ($CI->config->item('sae_memcache') == FALSE)
		{
			log_message('error','You\'ve closed SAE Memcache but was trying to use it.');
		}
		return memcache_set($this->_memcached, $id, array($data, time(), $ttl), 0, $ttl);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		key to be deleted.
	 * @return 	boolean 	true on success, false on failure
	 */
	public function delete($id)
	{
		return memcache_delete($this->_memcached, $id);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */
	public function clean()
	{
		return memcache_flush($this->_memcached);
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param 	null		type not supported in memcached
	 * @return 	mixed 		array on success, false on failure
	 */
	public function cache_info($type = NULL)
	{
		return memcache_get_stats($this->_memcached);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		FALSE on failure, array on success.
	 */
	public function get_metadata($id)
	{
		$stored = memcache_get($this->_memcached, $id);

		if (count($stored) !== 3)
		{
			return FALSE;
		}
		
		$CI =& get_instance();
		if ($CI->config->item('sae_memcache') == FALSE)
		{
			log_message('error','You\'ve closed SAE Memcache but was trying to use it.');
		}
		
		list($data, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Setup memcached.
	 */
	private function _setup_memcached()
	{
		// Try to load memcached server info from the config file.
		$CI =& get_instance();
		if ($CI->config->item('sae_memcache') == FALSE)
		{
			log_message('debug','You closed SAE Memcache.');
			return;
		}
		
		// SAE change
		$this->_memcached = memcache_init();
		
		if($this->_memcached == FALSE){
			log_message('error','Your SAE Memcache is not ready.');
		}
		
	}

	// ------------------------------------------------------------------------


	/**
	 * Is supported
	 *
	 * Returns FALSE if memcached is not supported on the system.
	 * If it is, we setup the memcached object & return TRUE
	 */
	public function is_supported()
	{
		$CI =& get_instance();
		if ($CI->config->item('sae_memcache') == FALSE)
		{
			log_message('debug','Your Memcache is disabled.');
			return FALSE;
		}
		
		$this->_setup_memcached();
		return TRUE;
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_memcached.php */
/* Location: ./system/libraries/Cache/drivers/Cache_memcached.php */