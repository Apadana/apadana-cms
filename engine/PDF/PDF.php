<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@parsnuke.com
 * @link: http://www.parsnuke.com
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright (C) 2012-2013 ParsNuke.com. All rights reserved.
 * @ParsNuke CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

require_once(engine_dir.'PDF/config/lang/eng.php');
require_once(engine_dir.'PDF/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true); 

// set document information
/*
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('');
$pdf->SetSubject('');
$pdf->SetKeywords('');
*/
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

//set some language-dependent strings
$pdf->setLanguageArray($lg); 

//============================================================+
// END OF FILE                                                 
//============================================================+
?>