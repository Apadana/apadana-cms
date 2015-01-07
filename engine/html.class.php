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

class html
{
    static function select($name, $options, $selected = null, $extra = null)
	{
        $extra = !empty($extra)? ' '.$extra : null;
        $html = '<select name="'.$name.'" size="1"'.$extra.'>';
        if(is_array($options) && count($options))
        foreach($options as $key => $val) 
		{
            $html .= '<option value="'.$key.'"'.($key==$selected? ' selected="selected"' : null).'>'.$val.'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static function radio($name, $options, $selected = null, $extra = null)
	{
        $html = null;
        $extra = !empty($extra)? ' '.$extra : null;
        if(is_array($options) && count($options))
        foreach($options as $key => $val) 
		{
            $html .= '<label><input type="radio" name="'.$name.'" value="'.$key.'"'.($key==$selected? ' checked="checked"' : null).$extra.' />'.$val.'</label> ';
        }
        return $html;
    }

    static function checkbox($options, $selected = null, $extra = null)
	{
        $html = null;
        $extra = !empty($extra)? ' '.$extra : null;
        if(is_array($options) && count($options))
        foreach($options as $key => $val) 
		{
            $key = explode('|', $key);
            $name = $key[0];
            $key = $key[1];
            $html .= '<label><input type="checkbox" name="'.$name.'" value="'.$key.'"'.($key==$selected? ' checked="checked"' : null).$extra.' />'.$val.'</label> ';
        }
        return $html;
    }

    static function input($type = 'text', $name, $value = null, $extra = null)
	{
        $extra = !empty($extra)? ' '.$extra : null;
        return '<input name="'.$name.'" type="'.$type.'" value="'.$value.'"'.$extra.' />';
    }

    static function textarea($name, $value = null, $size = null, $extra = null)
	{
        $extra = !empty($extra)? ' '.$extra : null;
        if (!empty($size)) 
		{
            $size = explode('x', $size);
            $size = ' rows="'.$size[1].'" cols="'.$size[0].'"';
        }
        return '<textarea name="'.$name.'"'.$size.$extra.'>'.$value.'</textarea>';
    }
}

?>