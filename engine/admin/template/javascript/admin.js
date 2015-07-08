/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCMS.ir. All rights reserved.
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
//arguments added in 1.0.5 Alpha 1 
apadana.infoBox = function(id, show, exit, sclass)
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
		sclass != "" ? $('#apadana-info-box').addClass(sclass) : null;
		$('#apadana-info-box').html($(id).html() + (exit == 1 ? '<center><strong style="color:red;cursor:pointer" onclick="apadana.infoBox(null, 0)">بستن پيام</strong></center>' : ""));
		var c = ($(window).width()-$('#apadana-info-box').width())/2,
			d = ($(window).height()-$('#apadana-info-box').height())/2.5;
		$('#apadana-info-box').css({left:c+'px',top:d+'px',position:'fixed'});
		
	}
	else if (show == 2) {
		if($('#apadana-info-box').css('display') != 'none' && $('#apadana-info-box-screen').css('display') != 'none'){
			var c = ($(window).width()-$('#apadana-info-box').width())/2,
			d = ($(window).height()-$('#apadana-info-box').height())/2.5;
			$('#apadana-info-box').css({left:c+'px',top:d+'px',position:'fixed'});
		}
	}
	else
	{
		$('#apadana-info-box-screen').fadeOut('slow');
		$('#apadana-info-box').fadeOut(200, function(){
			apadana.html('apadana-info-box', '');
		});
	}
}


function startintro(){
  var intro = introJs();
    intro.setOptions({
      steps: [
        {
          element: '#menu',
          intro: 'این ها میان بر ها هستند. از اینجا شما می توانید به بعضی از امکانات مدیریت دسترسی داشته باشید'
        },
        {
          element: '.content-tabs',
          intro: "اینجا تب های مدیریت قرار می گیرند. از اینجا می توانید به تمام قسمت های مدیریت دسترسی داشته باشید.پیشنهاد میکنم بعد از آموزش به همه تب ها یه سری بزنید."
        },
        {
          element: '#logo',
          intro: 'این آرم آپاداناست. در هر کجای مدیریت که باشید زمانی که روی این آرم کلیک کنید به صفحه اول مدیریت برمی گردید'
        },
        {
          intro: 'و در آخر: آپادانا یک سیستم بومی و متن باز است. اگر به کمکی نیاز داشتید می تونید به سایت apadanacms.ir سر بزنید. مطمئنم هر چه بیشتر با آپادانا کار کنید چیز های بیشتری کشف می کنید !!!!'
        }
      ],
      nextLabel: 'بعدی',
       prevLabel: 'قبلی',
       skipLabel: 'خروج',
       doneLabel: 'اتمام' 
    });

    intro.start();
}


$(document).ready(function(){
	tooltip.init();
	$(window).resize(
		function () {
			apadana.infoBox(null , 2);
		}
	);
})
