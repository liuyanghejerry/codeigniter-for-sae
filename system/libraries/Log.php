<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class CI_Log {

	protected $_log_path;
	protected $_threshold	= 1;
	protected $_date_fmt	= 'Y-m-d H:i:s';
	protected $_enabled	= TRUE;
	protected $_levels	= array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$config =& get_config();

		$this->_log_path = ($config['log_path'] != '') ? $config['log_path'] : APPPATH.'logs/';

		// deleted for sae
		// if ( ! is_dir($this->_log_path) OR ! is_really_writable($this->_log_path))
		// {
			// $this->_enabled = FALSE;
		// }

		if (is_numeric($config['log_threshold']))
		{
			$this->_threshold = $config['log_threshold'];
		}

		if ($config['log_date_format'] != '')
		{
			$this->_date_fmt = $config['log_date_format'];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}

		$level = strtoupper($level);

		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}

		$filepath = $this->_log_path.'log-'.date('Y-m-d').'.php';
		$message  = '';
		
		$ret = config_item('sae_storage');
		if($ret == FALSE || empty($ret)){
			sae_debug('Your SAE Storage is disabled.');
			return FALSE;
		}
		$storage_name = config_item('sae_storage_name');
		$storage_authority = config_item('sae_storage_authority');
		if( $storage_authority == 'secret' ){
			$storage_access = config_item('sae_storage_access');
			$storage_secret = config_item('sae_storage_secret');
			$s = new SaeStorage($storage_access, $storage_secret);
		}else{
			$s = new SaeStorage();
		}
		if( ! $s->fileExists($storage_name, $filepath) ){
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}
		
		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' --> '.$msg."\n";
		
		$part = $s->read($storage_name, $filepath);
		$ret = $s->write($storage_name, $filepath, $part.$message);
		if($ret == FALSE){
			sae_debug('There\'s an error during reading file. Error No.'.$s->errno());
			return FALSE;
		}
		
		return TRUE;
	}

}
// END Log Class

/* End of file Log.php */
/* Location: ./system/libraries/Log.php */