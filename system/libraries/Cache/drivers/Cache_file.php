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

class CI_Cache_file extends CI_Driver {

	protected $_cache_path;
	
	protected $_sae_storage;
	protected $_sae_storage_name;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$CI =& get_instance();
		
		$path = $CI->config->item('cache_path');
		$this->_sae_storage_name = $CI->config->item('sae_storage_name');
		if( $this->_sae_storage_name == FALSE || empty($this->_sae_storage_name) ){
			return;
		}
		$sae_storage_secret = $CI->config->item('sae_storage_secret');
		$sae_storage_access = $CI->config->item('sae_storage_access');
		
		$this->_sae_storage = new SaeStorage($sae_storage_access, $sae_storage_secret);
		
		
	
		$this->_cache_path = ($path == '') ? APPPATH.'cache/' : $path;
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */
	public function get($id)
	{
		if ( ! $this->_sae_storage->fileExists($this->_sae_storage_name, $this->_cache_path.$id))
		{
			return FALSE;
		}
		
		$data = $this->_sae_storage->read($this->_sae_storage_name, $this->_cache_path.$id);
		$data = unserialize($data);
		
		if (time() >  $data['time'] + $data['ttl'])
		{
			$this->_sae_storage->delete($this->_sae_storage_name, $this->_cache_path.$id);
			return FALSE;
		}
		
		return $data['data'];
	}

	// ------------------------------------------------------------------------

	/**
	 * Save into cache
	 *
	 * @param 	string		unique key
	 * @param 	mixed		data to store
	 * @param 	int			length of time (in seconds) the cache is valid 
	 *						- Default is 60 seconds
	 * @return 	boolean		true on success/false on failure
	 */
	public function save($id, $data, $ttl = 60)
	{		
		$contents = array(
				'time'		=> time(),
				'ttl'		=> $ttl,			
				'data'		=> $data
			);
		
		if ($this->_sae_storage->write($this->_sae_storage_name, $this->_cache_path.$id, serialize($contents)))
		{
			return TRUE;			
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		unique identifier of item in cache
	 * @return 	boolean		true on success/false on failure
	 */
	public function delete($id)
	{
		return $this->_sae_storage->delete($this->_sae_storage_name, $this->_cache_path.$id);
	}

	// ------------------------------------------------------------------------

	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */	
	public function clean()
	{
		return $this->_sae_storage->deleteFolder($this->_sae_storage_name, $this->_cache_path);
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * Not supported by file-based caching
	 *
	 * @param 	string	user/filehits
	 * @return 	mixed 	FALSE
	 */
	public function cache_info($type = NULL)
	{
		return FALSE;
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
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Is supported
	 *
	 * In the file driver, check to see that the cache directory is indeed writable
	 * 
	 * @return boolean
	 */
	public function is_supported()
	{
		$CI =& get_instance();
		$able = $CI->config->item('sae_storage_name');
		if( $able == FALSE || empty($able) ){
			return FALSE;
		}
		return TRUE;
	}

	// ------------------------------------------------------------------------
}
// End Class

/* End of file Cache_file.php */
/* Location: ./system/libraries/Cache/drivers/Cache_file.php */