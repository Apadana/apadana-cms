<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2013 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function wysiwyg_textarea($name, $value, $config = 'apadana', $class = null, $extra = null, $cols = 50, $rows = 10)
{
    if ($config == 'Class')
	{
		global $page;
		set_head('<script language="JavaScript" src="'.url.'engine/editor/ckeditor/ckeditor.js" type="text/javascript"></script>');
        $class = !empty($class)? ' '.$class : null;
        $extra = !empty($extra)? ' '.$extra : null;
	    return '<textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'" class="ckeditor'.$class.'"'.$extra.'>'.$value.'</textarea>';
	}

	global $options;

	require_once root_dir.'engine/editor/ckeditor/ckeditor.php';
	$ckeditor = new CKEditor();
	$ckeditor->returnOutput = true;
	$ckeditor->basePath = url.'engine/editor/ckeditor/';
	$ckeditor->textareaAttributes['id'] = 'textarea_'.trim(str_replace(array('[', ']', '-', '__', ' '), '_', $name), '_');
	$ckeditor->config['smiley_path'] = url.'engine/images/smiles/';
	$ckeditor->config['uiColor'] = $options['editor-color'];
	$ckeditor->config['smiley_images'] = array(
		'1.gif','2.gif','3.gif','4.gif','5.gif','6.gif','7.gif','8.gif','9.gif','10.gif','11.gif','12.gif','13.gif','14.gif','15.gif','16.gif','17.gif','18.gif','19.gif','20.gif','21.gif','22.gif','23.gif','24.gif','25.gif','26.gif','27.gif','28.gif','29.gif','30.gif','31.gif','32.gif','33.gif','34.gif','35.gif','36.gif','37.gif','38.gif','39.gif','40.gif','41.gif','42.gif','43.gif','44.gif','45.gif','46.gif','47.gif','48.gif','49.gif','50.gif','51.gif','52.gif','53.gif','54.gif','55.gif','56.gif','57.gif','58.gif','59.gif','60.gif','61.gif','62.gif','63.gif','64.gif','65.gif','66.gif','67.gif','68.gif','69.gif','70.gif','71.gif','72.gif','73.gif','74.gif','75.gif'
	);
	$ckeditor->config['smiley_descriptions'] = array(
		'angel','Happy','Sad','smiley4','smiley5','smiley6','smiley7','smiley8','smiley9','smiley10','smiley11','smiley12','smiley13','smiley14','smiley15','smiley16','smiley17','smiley18','smiley19','smiley20','smiley21','smiley22','smiley23','smiley24','smiley25','smiley26','smiley27','smiley28','smiley29','smiley30','smiley31','smiley32','smiley33','smiley34','smiley35','smiley36','smiley37','smiley38','smiley39','smiley40','smiley41','smiley42','smiley43','smiley44','smiley45','smiley46','smiley47','smiley48','smiley49','smiley50','smiley51','smiley52','smiley53','smiley54','smiley55','smiley56','smiley57','smiley58','smiley59','smiley60','smiley61','smiley62','smiley63','smiley64','smiley65','smiley66','smiley67','smiley68','smiley69','smiley70','smiley71','smiley72','smiley73','smiley74','smiley75'
	);

	switch($config)
	{
	    case 'Basic':
		$ckeditor->config['toolbar'] = array(
			array( 'FontSize','Bold','Italic','TextColor','-','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','Smiley' )
		);	
		break;	

	    case 'BBcode':
		$ckeditor->config['extraPlugins'] = 'bbcode';
		$ckeditor->config['removePlugins'] = 'bidi,button,dialogadvtab,div,filebrowser,flash,format,forms,horizontalrule,iframe,indent,justify,liststyle,pagebreak,showborders,stylescombo,table,tabletools,templates';
		$ckeditor->config['disableObjectResizing'] = true;
		$ckeditor->config['fontSize_sizes'] = '30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%';
		$ckeditor->config['toolbar'] = array(
			array( 'Source', '-', 'NewPage','-','Undo','Redo' ),
			array( 'Find','Replace','-','SelectAll','RemoveFormat' ),
			array( 'Link', 'Unlink', 'Image', 'Smiley','SpecialChar' ),
			array( 'Bold', 'Italic','Underline' ),
			array( 'FontSize' ),
			array( 'TextColor' ),
			array( 'NumberedList','BulletedList','-','Blockquote' ),
			array( 'Maximize' )
		);
		break;
		
	    case 'apadana':
		if (group_admin == 1 && member::check_admin_page_access('media'))
		{
			global $options;
			$ckeditor->config['filebrowserBrowseUrl'] = url.'?admin='.$options['admin'].'&section=media&noTemplate=true&editor=true';
		}		
		break;
		
	    default:
		$ckeditor->config['toolbar'] = array(
			array( 'Source','FitWindow','-' ),
			array( 'Cut','Copy','Paste','PasteText','PasteWord','-','Print' ),
			array( 'Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat',  'Maximize' ),
			array( 'Link','Unlink','Anchor','StrikeThrough','-','Subscript','Superscript' ),
			array( 'OrderedList','UnorderedList','-','Outdent','Indent', 'NumberedList', 'BulletedList', 'Blockquote' ),
			array( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','Table','Rule','SpecialChar','PageBreak' ),
			array( 'FontName','FontSize','Bold','Italic','Underline','TextColor','BGColor','Smiley', 'Image' ),
		);
		break;		
	}
	
	return $ckeditor->editor($name, $value, $config);
}

?>