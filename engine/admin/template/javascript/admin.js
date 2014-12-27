/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2013 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
**/

apadana.slug = function(text)
{
	text = text.replace(/ {2,}/g, '-');
	text = text.replace(/\s/g, '-');
	text = text.replace(/!/g, '-');
	text = text.replace(/@/g, '-');
	text = text.replace(/#/g, '-');
	text = text.replace(/\$/g, '-');
	text = text.replace(/÷/g, '-');
	text = text.replace(/%/g, '-');
	text = text.replace(/~/g, '-');
	text = text.replace(/\(/g, '-');
	text = text.replace(/\)/g, '-');
	text = text.replace(/_/g, '-');
	text = text.replace(/=/g, '-');
	text = text.replace(/\+/g, '-');
	text = text.replace(/\|/g, '-');
	text = text.replace(/\//g, '-');
	text = text.replace(/\\/g, '-');
	text = text.replace(/'/g, '-');
	text = text.replace(/"/g, '-');
	text = text.replace(/&/g, '-');
	text = text.replace(/\?/g, '-');
	text = text.replace(/؟/g, '-');
	text = text.replace(/</g, '-');
	text = text.replace(/>/g, '-');
	text = text.replace(/,/g, '-');
	text = text.replace(/\./g, '-');
	text = text.replace(/،/g, '-');
	text = text.replace(/:/g, '-');
	text = text.replace(/-{2,}/g, '-');
	text = text.replace(/^-+|-+$/g, '');
	return text;
}

apadana.message = function(message, type)
{
	return '<div class="apadana-message-'+type+'"><span>'+message+'</span></div>'
}

apadana.infoBox = function(id, show)
{
	if (!apadana.$('apadana-info-box-screen'))
	{
		$('<div/>', {
			id: 'apadana-info-box-screen',
			style: 'display:none',
		}).appendTo('body');

		$('<div/>', {
			id: 'apadana-info-box',
			style: 'display:none',
		}).appendTo('body');
	}
	if (show == 1)
	{
		$('#apadana-info-box-screen').fadeIn('slow');
		$('#apadana-info-box').fadeIn('slow');
		$('#apadana-info-box').html($(id).html() + '<center><strong style="color:red;cursor:pointer" onclick="apadana.infoBox(null, 0)">بستن پيام</strong></center>');
		var c = ($(window).width()-$('#apadana-info-box').width())/2,
			d = ($(window).height()-$('#apadana-info-box').height())/2.5;
		$('#apadana-info-box').css({left:c+'px',top:d+'px',position:'fixed'});
		
	}
	else
	{
		$('#apadana-info-box-screen').fadeOut('slow');
		$('#apadana-info-box').fadeOut(200, function(){
			apadana.html('apadana-info-box', '');
		});
	}
}

$(document).ready(function(){
	tooltip.init()
})
