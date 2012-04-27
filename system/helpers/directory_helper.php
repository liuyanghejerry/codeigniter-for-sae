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
 * CodeIgniter Directory Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
if ( ! function_exists('directory_map'))
{
	function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
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
		$loop = TRUE;
		$files = array();
		while( $loop){
			$ret = $s->getListByPath($storage_name, $source_dir, 100, $num, TRUE );
			$total = count($ret['dirs']) + count($ret['files']) ;
			if( ! $total ) $loop = FALSE;
			foreach($ret['dirs'] as $dir) {
				if( $directory_depth == 1){
						$files[$dir['name']] = array();
					}else{
						$files[$dir['name']] = directory_map($dir['fullName'], --$directory_depth, $hidden);
					}
				$num ++;
				$total --;
			}
			foreach($ret['files'] as $file){
				array_push($files, $file['Name']);
				$num ++;
				$total --;
			}
		}
		return $files;
	}
}


/* End of file directory_helper.php */
/* Location: ./system/helpers/directory_helper.php */