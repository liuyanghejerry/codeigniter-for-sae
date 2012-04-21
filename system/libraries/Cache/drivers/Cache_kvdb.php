<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter For SAE
 * @author		liuyanghejerry
 * @copyright	MIT
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter KVDB Caching Class
 *
 * @package		CodeIgniter For SAE
 * @subpackage	Libraries
 * @category	Core
 * @author		liuyanghejerry
 * @link
 */

class CI_Cache_kvdb extends CI_Driver {

	 protected $_sae_kvdb;
	 
	public function __construct()
	{
		$CI =& get_instance();
		
		$this->_sae_kvdb = $CI->config->item('sae_kvdb');
		if( $this->_sae_kvdb == FALSE || empty($this->_sae_kvdb) ){
			log_message('debug', 'SAE KVDB is disabled.');
			return;
		}
		
		$this->_sae_kvdb = new SaeKV();
		$ret = $this->_sae_kvdb->init();
		if($ret == FALSE){
			log_message('error', 'SAE KVDB init error, which error message is: '.$this->_sae_kvdb->errmsg());
		}
	}
	
	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */
	
	public function get($id)
	{
		$data = unserialize($this->_sae_kvdb->get($id));
		$ttl = $data['ttl'];
		$time = $data['time'];
		if( time() > $ttl + $time ){
			$this->_sae_kvdb->delete($id);
			return FALSE;
		}
		$data = $data['data'];
		return $data;
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Save
	 *
	 * @param 	string		Unique Key
	 * @param 	mixed		Data to store
	 * @param 	int			Length of time (in seconds) to cache the data
	 *
	 * @return 	boolean		true on success/false on failure
	 */
	public function save($id, $data, $ttl = 60)
	{
		$data = array(
				'data' => $data,
				'time' => time(),
				'ttl' => $ttl
				);
		$data = serialize($data);
		return $this->_sae_kvdb->set($id, $data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		unique identifier of the item in the cache
	 * @return 	boolean		true on success/false on failure
	 */
	public function delete($id)
	{
		
		return $this->_sae_kvdb->delete($id);
	}

	// ------------------------------------------------------------------------

	/**
	 * Clean the cache
	 *
	 * @return 	boolean		true on success/false on failure
	 */
	public function clean()
	{
		$stack = array();
		$ret = $kv->pkrget('', 100);
		while (TRUE) {                    
			if(!is_array($ret)){
				return FALSE;
			}
			$stack = array_merge($stack, $ret);                     
			end($ret);                                
			$start_key = key($ret);
			$i = count($ret);
			if ($i < 100) break;
			$ret = $kv->pkrget('', 100, $start_key);
		}
		foreach($stack as $row){
			$this->delete(key($row));
		}
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param 	string		user/filehits
	 * @return 	boolean		FALSE
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
	 * @return 	boolean		FALSE
	 */
	public function get_metadata($id)
	{
		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Is this caching driver supported on the system?
	 * 
	 *
	 * @return boolean    true / false
	 */
	public function is_supported()
	{
		$CI =& get_instance();
		$able = $CI->config->item('sae_kvdb');
		if( $able == FALSE || empty($able) ){
			return FALSE;
		}
		return TRUE;
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_dummy.php */
/* Location: ./system/libraries/Cache/drivers/Cache_dummy.php */