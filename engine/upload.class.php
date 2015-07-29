<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

class upload
{
	static function file($file, $path, $filename = null)
    {
		if (empty($file['name']) || $file['name'] == 'none' || $file['size'] < 1)
		{
			$upload['error'] = 1;
			return $upload;
		}

		if (!$filename)
		{
			$filename = $file['name'];
		}
		
		$upload['original_filename'] = self::safe($file['name']);
		$filename = self::safe($filename);
		$moved = @move_uploaded_file($file['tmp_name'], $path.'/'.$filename);
		
		if (!$moved)
		{
			$upload['error'] = 2;
			return $upload;
		}
		@apadana_chmod($path.'/'.$filename, 0644);
		@unlink($file['tmp_name']);
		
		$upload['filename'] = $filename;
		$upload['path'] = $path;
		$upload['type'] = $file['type'];
		$upload['size'] = $file['size'];
		return $upload;
    }
	
	static function safe($file)
    {
		return preg_replace('#/$#', null, $file); // Make the filename safe
    }
}
