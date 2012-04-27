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
 * CodeIgniter File Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/file_helpers.html
 */

// ------------------------------------------------------------------------

/**
 * Read File
 *
 * Opens the file specfied in the path and returns it as a string.
 *
 * @access	public
 * @param	string	path to file
 * @return	string
 */
if ( ! function_exists('read_file'))
{
	function read_file($file)
	{
		
		$ret = config_item('sae_storage');
		if($ret == FALSE || empty($ret)){
			log_message('debug','Your SAE Storage is disabled.');
			return FALSE;
		}
		
		$storage_name = config_item('sae_storage_name');
		$storage_authority = config_item('sae_storage_authority');
		if($storage_authority == 'secret'){
			$storage_access = config_item('sae_storage_access');
			$storage_secret = config_item('sae_storage_secret');
			$s = new SaeStorage($storage_access, $storage_secret);
		}else{
			$s = new SaeStorage();
		}
		
		$ret = $s->read( $storage_name , $file);
		
		if($ret == FALSE){
			log_message('debug', 'There\'s an error during reading file. Error No.'.$s->errno());
			return FALSE;
		}

		return $ret;
	}
}

// ------------------------------------------------------------------------

/**
 * Write File
 *
 * Writes data to the file specified in the path.
 * Creates a new file if non-existent.
 *
 * @access	public
 * @param	string	path to file
 * @param	string	file data
 * @return	bool
 */
if ( ! function_exists('write_file'))
{
	function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		$ret = config_item('sae_storage');
		if($ret == FALSE || empty($ret)){
			log_message('debug','Your SAE Storage is disabled.');
			return FALSE;
		}
		
		$storage_name = config_item('sae_storage_name');
		$storage_authority = config_item('sae_storage_authority');
		if($storage_authority == 'secret'){
			$storage_access = config_item('sae_storage_access');
			$storage_secret = config_item('sae_storage_secret');
			$s = new SaeStorage($storage_access, $storage_secret);
		}else{
			$s = new SaeStorage();
		}
		
		
		$ret = $s->write($storage_name, $path, $data);
		if($ret == FALSE){
			log_message('debug', 'There\'s an error during reading file. Error No.'.$s->errno());
			return FALSE;
		}

		return TRUE;
	}
}

// ------------------------------------------------------------------------

/**
 * Delete Files
 *
 * Deletes all files contained in the supplied directory path and directory itself.
 *
 * @access	public
 * @param	string	path to file
 * @param	bool	currently, any files or directories will be deleted wheather true or false
 * @return	bool
 */
if ( ! function_exists('delete_files'))
{
	function delete_files($path, $del_dir = FALSE, $level = 0)
	{
		$ret = config_item('sae_storage');
		if($ret == FALSE || empty($ret)){
			log_message('debug','Your SAE Storage is disabled.');
			return FALSE;
		}
		
		$storage_name = config_item('sae_storage_name');
		$storage_authority = config_item('sae_storage_authority');
		if($storage_authority == 'secret'){
			$storage_access = config_item('sae_storage_access');
			$storage_secret = config_item('sae_storage_secret');
			$s = new SaeStorage($storage_access, $storage_secret);
		}else{
			$s = new SaeStorage();
		}
		
		
		$path = rtrim($path, DIRECTORY_SEPARATOR);
		$ret = $s->deleteFolder($storage_name, $path);
		
		return $ret;
	}
}

// ------------------------------------------------------------------------

/**
 * Get Filenames
 *
 * Reads the specified directory and builds an array containing the filenames.
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
if ( ! function_exists('get_filenames'))
{
	function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE)
	{
		$ret = config_item('sae_storage');
		if($ret == FALSE || empty($ret)){
			log_message('debug','Your SAE Storage is disabled.');
			return FALSE;
		}
		
		$storage_name = config_item('sae_storage_name');
		$storage_authority = config_item('sae_storage_authority');
		if($storage_authority == 'secret'){
			$storage_access = config_item('sae_storage_access');
			$storage_secret = config_item('sae_storage_secret');
			$s = new SaeStorage($storage_access, $storage_secret);
		}else{
			$s = new SaeStorage();
		}
		
		
		$num = 0;
		$files = array();
		$loop = TRUE;
		while($loop){
			$ret = $s->getListByPath($storage_name, $source_dir, 100, $num, TRUE );
			$total = count($ret['dirs']) + count($ret['files']) ;
			if( ! $total ) $loop = FALSE;
			foreach($ret['dirs'] as $dir) {
				if( $_recursion ){
					$files = array_merge( $files, get_filenames($dir['fullName'], $include_path, $_recursion) );
				}else{
					if( $include_path ){
						array_push($files, $dir['fullName']);
					}else{
						array_push($files, $dir['name']);
					}
				}
				$num ++;
				$total --;
			}
			foreach($ret['files'] as $file){
				if( $include_path ){
					array_push($files, $file['fullName']);
				}else{
					array_push($files, $file['Name']);
				}
				$num ++;
				$total --;
			}
		}
 
		return $files;
	}
}

// --------------------------------------------------------------------

/**
 * Get Directory File Information
 *
 * Reads the specified directory and builds an array containing the filenames,
 * filesize, dates, and permissions
 *
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	Look only at the top level directory specified?
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
if ( ! function_exists('get_dir_file_info'))
{
	function get_dir_file_info($source_dir, $top_level_only = TRUE, $_recursion = FALSE)
	{
		return FALSE;
	}
}

// --------------------------------------------------------------------

/**
* Get File Info
*
* Given a file and path, returns the name, path, size, date modified
* Second parameter allows you to explicitly declare what information you want returned
* Options are: name, server_path, size, date, readable, writable, executable, fileperms
* Returns FALSE if the file cannot be found.
*
* @access	public
* @param	string	path to file
* @param	mixed	array or comma separated string of information returned
* @return	array
*/
if ( ! function_exists('get_file_info'))
{
	function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date'))
	{

		return FALSE;
	}
}

// --------------------------------------------------------------------

/**
 * Get Mime by Extension
 *
 * Translates a file extension into a mime type based on config/mimes.php.
 * Returns FALSE if it can't determine the type, or open the mime config file
 *
 * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
 * It should NOT be trusted, and should certainly NOT be used for security
 *
 * @access	public
 * @param	string	path to file
 * @return	mixed
 */
if ( ! function_exists('get_mime_by_extension'))
{
	function get_mime_by_extension($file)
	{
		$extension = strtolower(substr(strrchr($file, '.'), 1));

		global $mimes;

		if ( ! is_array($mimes))
		{
			if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'))
			{
				include(APPPATH.'config/'.ENVIRONMENT.'/mimes.php');
			}
			elseif (is_file(APPPATH.'config/mimes.php'))
			{
				include(APPPATH.'config/mimes.php');
			}

			if ( ! is_array($mimes))
			{
				return FALSE;
			}
		}

		if (array_key_exists($extension, $mimes))
		{
			if (is_array($mimes[$extension]))
			{
				// Multiple mime types, just give the first one
				return current($mimes[$extension]);
			}
			else
			{
				return $mimes[$extension];
			}
		}
		else
		{
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Symbolic Permissions
 *
 * Takes a numeric value representing a file's permissions and returns
 * standard symbolic notation representing that value
 *
 * @access	public
 * @param	int
 * @return	string
 */
if ( ! function_exists('symbolic_permissions'))
{
	function symbolic_permissions($perms)
	{
		if (($perms & 0xC000) == 0xC000)
		{
			$symbolic = 's'; // Socket
		}
		elseif (($perms & 0xA000) == 0xA000)
		{
			$symbolic = 'l'; // Symbolic Link
		}
		elseif (($perms & 0x8000) == 0x8000)
		{
			$symbolic = '-'; // Regular
		}
		elseif (($perms & 0x6000) == 0x6000)
		{
			$symbolic = 'b'; // Block special
		}
		elseif (($perms & 0x4000) == 0x4000)
		{
			$symbolic = 'd'; // Directory
		}
		elseif (($perms & 0x2000) == 0x2000)
		{
			$symbolic = 'c'; // Character special
		}
		elseif (($perms & 0x1000) == 0x1000)
		{
			$symbolic = 'p'; // FIFO pipe
		}
		else
		{
			$symbolic = 'u'; // Unknown
		}

		// Owner
		$symbolic .= (($perms & 0x0100) ? 'r' : '-');
		$symbolic .= (($perms & 0x0080) ? 'w' : '-');
		$symbolic .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

		// Group
		$symbolic .= (($perms & 0x0020) ? 'r' : '-');
		$symbolic .= (($perms & 0x0010) ? 'w' : '-');
		$symbolic .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

		// World
		$symbolic .= (($perms & 0x0004) ? 'r' : '-');
		$symbolic .= (($perms & 0x0002) ? 'w' : '-');
		$symbolic .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

		return $symbolic;
	}
}

// --------------------------------------------------------------------

/**
 * Octal Permissions
 *
 * Takes a numeric value representing a file's permissions and returns
 * a three character string representing the file's octal permissions
 *
 * @access	public
 * @param	int
 * @return	string
 */
if ( ! function_exists('octal_permissions'))
{
	function octal_permissions($perms)
	{
		return substr(sprintf('%o', $perms), -3);
	}
}


/* End of file file_helper.php */
/* Location: ./system/helpers/file_helper.php */