/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2013 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
**/

var apadana = {
	browser:
	{
		ie: !!(window.attachEvent && !window.opera),
		opera: !!window.opera,
		webKit: navigator.userAgent.indexOf('AppleWebKit/') > -1,
		gecKo: navigator.userAgent.indexOf('Gecko') > -1 && navigator.userAgent.indexOf('KHTML') == -1,
		mobileSafari: !!navigator.userAgent.match(/Apple.*Mobile.*Safari/)
	},

    ajax: function(a)
    {
        if (typeof(a.id) == 'undefined' || a.id == '' || a.id == null)
			a.id = 'no-id';

		if (typeof(a.method) == 'undefined' || a.method == '' || a.method == null)
			a.method = 'POST';

		if (typeof(a.loading) == 'undefined' || a.loading == '' || a.loading == null)
			a.loading = 'yes';

		if (typeof(a.action) == 'undefined' || a.action == '' || a.action == null)
			a.action = 'index.php';

		if (typeof(a.data) == 'undefined')
			a.data = null;

		if (typeof(a.error_alert) == 'undefined')
			a.error_alert = 'yes';

		if (typeof(a.json) == 'undefined')
			a.json = 'no';

		if (typeof(a.cache) == 'undefined')
			a.cache = 'no';

		a.action = a.action.replace(/&amp;/gi,'&');
		if (a.method.toLowerCase() == 'get' && a.data != '' && a.data != null) a.action = a.action+ (a.action.indexOf('?')=='-1'? '?' : '&') +a.data;
		if (a.cache == 'no') a.action = a.action+ (a.action.indexOf('?')=='-1'? '?' : '&') +'_='+this.random();

		var xmlhttp=false;
        if (!xmlhttp)
		{
            if (window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
            else if (window.ActiveXObject)
            xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
        }
		else if (window.XMLHttpRequest)
		{
            xmlHttp = new XMLHttpRequest();
        }

		a.beforeSend&&a.beforeSend();
		if (typeof(a.load) != 'undefined' && a.load != '' && a.load != null && a.id != 'no-id'){this.showID(a.id);this.html(a.id, a.load)}/* Chrom */
		if (a.loading == 'yes') this.loading(1);

        xmlhttp.open(a.method, a.action, true);
        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp.onreadystatechange = function()
		{
            if (xmlhttp.readyState==4 && xmlhttp.status == 200)
			{
				if (a.json != 'yes')
				{
					var text = apadana.getScript(xmlhttp.responseText);
					if (a.id != 'no-id'){apadana.showID(a.id);apadana.html(a.id, text)}
				}
				else
				{
					var text = apadana.evalJSON(xmlhttp.responseText);
				}
                if (a.loading == 'yes') apadana.loading(0);
				a.success&&a.success(text);
            }
			else if (xmlhttp.readyState==0 || xmlhttp.readyState==1 || xmlhttp.readyState==2 || xmlhttp.readyState==3)
			{
                /* beforeSend => loading! */
            }
			else
			{
                if (typeof(a.note) != 'undefined' && a.note != '' && a.note != null && a.id != 'no-id'){apadana.showID(a.id);apadana.html(a.id, a.note)}
                if (a.loading == 'yes') apadana.loading(0);
				a.error&&a.error();
                if (a.error_alert == 'yes') alert('در ارتباط خطایی رخ داده مجدد تلاش کنید');
            }
        }
        xmlhttp.send(a.data);
    },

    serialize: function(ID)
    {
        oForm = this.$(ID);
        var aParams = new Array();
        for (var i = 0 ; i < oForm.elements.length; i++)
		{
         	var element = oForm.elements[i];
			if (element.type)
			{
				if (!element.hasAttribute('name')) continue;
				switch(element.type.toLowerCase())
				{
					case 'checkbox':
					case 'radio':
					if (!element.checked) continue;
					var sParam = element.name;
					sParam += '=';
					sParam += encodeURIComponent(element.value);
					aParams.push(sParam);
					break;

					case 'select-one':
					var index = element.selectedIndex;
					var sParam = element.name;
					sParam += '=';
					sParam += index >= 0 ? encodeURIComponent(element.options[index].value) : null;
					aParams.push(sParam);
					break;

					case 'select-multiple':
					var length = element.length;
					if (!length) continue;
					for (var b = 0; b < length; b++)
					{
						var opt = element.options[b];
						if (!opt.selected) continue;
						var sParam = element.name;
						sParam += '=';
						sParam += encodeURIComponent(opt.hasAttribute('value')? opt.value : opt.text);
						aParams.push(sParam);
					}
					break;

					default:
					var sParam = element.name;
					sParam += '=';
					sParam += encodeURIComponent(element.value);
					aParams.push(sParam);
					break;
				}
			}
         }
         return aParams.join('&');
    },

    getScript: function(str)
    {
        var reg = new RegExp('(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)', 'img');
        var s=1;
        while(s = reg.exec(str))
        {
    	   this.doEval( s[1] );
        }
        return str;
    },

    doEval: function(js)
    {
    	if (js!=null)
		{
        	var sc = document.createElement('script');
        	jsCode = js;
        	sc.type = 'text/javascript';
        	document.getElementsByTagName('head')[0].appendChild(sc);
        	sc.text = 'try{eval(jsCode);}catch(e){}jsCode="";';
        	sc.text = "\/\/ :-)";
    	}
    },
	
	isJSON: function(json)
	{
    	json = this.trim(json);
		if (json == '' || json.substr(0, 1) != '{') return false;
		json = json.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, '');
		return (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(json);
	},

    evalJSON: function(json)
    {
    	json = this.trim(json);
		if (this.isJSON(json))
		{
        	return eval('(' + json + ')');
    	}
		else
		{
        	return false;
		}
    },

	scroll: function(a, c)
	{
		var b = typeof(c) == 'undefined'? 800 : c;
		$('html,body').animate({scrollTop:$(a).offset().top}, b)
	},
	
    loading: function(action)
    {
		if (typeof(cursorType) == 'undefined')
		{
			cursorType = document.getElementsByTagName('html')[0].style.cursor;
		}
		if (!apadana.$('apadana-loading-layer'))
		{
			$('<div/>', {
				id: 'apadana-loading-layer',
				style: 'display:none',
				html: '<div id="apadana-loading-layer-text">در حال بارگذاری اطلاعات ...</div>'
			}).appendTo('body');
		}
		if (action == 1)
		{
			$('html,body').css('cursor', 'wait');
			var c = ($(window).width()-$('#apadana-loading-layer').width())/2,
				d = ($(window).height()-$('#apadana-loading-layer').height())/2;
			$('#apadana-loading-layer').css({left:c+'px',top:d+'px',position:'fixed',zIndex:'999999'});
			$('#apadana-loading-layer').fadeTo('slow',0.6)
		}
		else
		{
			$('html,body').css('cursor', cursorType);
			$('#apadana-loading-layer').fadeOut('slow')
		}
    },

	changeTab: function(tabID, totalTabs, a, id)
	{
		if (typeof(id) == 'undefined')
		{
			id = 'id';
		}
		for (i = 1; i <= totalTabs; i++)
		{
			apadana.changeClass('tab-'+id+'-' + i, 'tab-off');
			apadana.hideID('option-'+id+'-' + i);
		}
		apadana.changeClass('tab-'+id+'-' + tabID, 'tab-on');
		apadana.showID('option-'+id+'-' + tabID);
		a&&a(tabID, totalTabs);
	},

	popupWindow: function(url, name, width, height)
	{
		settings = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes';

		if (width)
		{
			settings = settings+',width='+width;
		}

		if (height)
		{
			settings = settings+',height='+height;
		}
		window.open(url, name, settings);
	},

    $: function(id)
    {
		if (typeof(id) == 'string')
		{
			return document.getElementById(id);
		}
		else
		{
			return id;
		}
    },

	$$: function(a, b)
	{
		var e = this.elementsByClass(a);
		for (i=0;i<e.length;i++)
		{
			b&&b(e[i]);
		}
	},

	elementsByClass: function(className)
	{
		var children = document.getElementsByTagName('*') || document.all;
		var elements = new Array();

		for (var i = 0; i < children.length; i++)
		{
			var child = children[i];
            if (this.trim(child.className) == '') continue;
			var classNames = child.className.split(' ');
			for (var j = 0; j < classNames.length; j++)
			{
                if (this.trim(classNames[j]) == '') continue;
				if (classNames[j] == className)
				{
					elements.push(child);
					break;
				}
			}
		}
		return elements;
	},

	tag: function(a, b)
	{
		var t = document.getElementsByTagName(a);
		if (t.length>0)
		{
			for (i=0;i<t.length;i++)
			{
				b&&b(t[i]);
			}
		}
	},
	
    html: function(id, code)
    {
        if (this.$(id))
		{
			if (typeof(code) == 'undefined')
				return this.$(id).innerHTML;
			else
				this.$(id).innerHTML = code;
		}
        else
			return false;
    },

    attr: function(id, name, data)
    {
        if (this.$(id))
		{
			if (typeof(data) == 'undefined')
            {
    			if (this.$(id).hasAttribute(name))
				    return this.$(id).getAttribute(name);
            }
			else
				return this.$(id).setAttribute(name, data);
		}
		return false;
    },

    value: function(id, data)
    {
        if (this.$(id))
		{
			if (typeof(data) == 'undefined')
				return this.$(id).value;
			else
				this.$(id).value = data;
		}
        else
			return false;
    },

    hideID: function(id)
    {
        if ( this.$(id) )
        this.$(id).style.display='none';
    },

    showID: function(id)
    {
        if ( this.$(id) )
        this.$(id).style.display='block';
    },

    changeShow: function(id)
    {
    	if (this.$(id).style.display == 'none')
        this.$(id).style.display='';
    	else
        this.$(id).style.display='none'
    },

    changeClass: function(id, newclass)
    {
        if ( this.$(id) )
        this.$(id).className=newclass;
    },

    changeSrc: function(id, newsrc)
    {
        if ( this.$(id) )
        this.$(id).src=newsrc;
    },

    changeDisable: function(id)
    {
    	if (this.$(id).disabled == 'on')
        this.$(id).disabled='';
    	else
        this.$(id).disabled='on'
    },

	remove: function(element)
	{
		element = this.$(element);
		element.parentNode.removeChild(element);
		return element;
	},
	
    setCookie: function(name, value, expirationInDays)
    {
    	if (expirationInDays)
		{
    		var date = new Date();
    		date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
    		var expires = '; expires=' + date.toGMTString();
    	}
		else
		{
    		var expires = '';
    	}
    	document.cookie = name + '=' + value + expires + '; path=/';
    },

    getCookie: function(name)
    {
    	var MY_COOKIE = 'noCookie';
    	var namePattern = name + '=';
    	var cookies = document.cookie.split(';');
    	for (var i = 0, n = cookies.length; i < n; i++)
		{
    	    var c = cookies[i];
    	    while (c.charAt(0) == ' ') c = c.substring(1, c.length)
    	    if (c.indexOf(namePattern) == 0)
    		MY_COOKIE = c.substring(namePattern.length, c.length);
    	}
    	return MY_COOKIE;
    },

    location: function(url)
    {
        document.location = url;
    },

	random: function(start, end)
	{
		if (typeof(start) == 'undefined')
			return(Math.floor(Math.random() * 986354) * Math.floor(Math.random() * 694324));
		else
			return(start + Math.floor(Math.random() * (end-start)));
	},
	
    trim: function(string)
    {
		return string.replace(/^\s+|\s+$/g, '');
    },

	stripTags: function(string)
	{
		return string.replace(/<\/?[^>]+>/gi, '');
	},

    setTitle: function(title)
    {
        if (typeof(title)=='undefined' && title == '') return;
        document.title = title;
    },

    confirm: function(msg, a)
    {
        if (typeof(msg) == 'undefined' || msg == '')
        msg = "آیا از انجام این کار اطمینان دارید؟\nعملیات قابل بازگشت نیست!";
        if (!confirm(msg)) return;
        return a&&a();
    },

	unHTMLchars: function(text)
	{
		text = text.replace(/&lt;/gi, '<');
		text = text.replace(/&gt;/gi, '>');
		text = text.replace(/&nbsp;/gi, ' ');
		text = text.replace(/&quot;/gi, '"');
		text = text.replace(/&amp;/gi, '&');
		return text;
	},

	HTMLchars: function(text)
	{
		text = text.replace(new RegExp('&(?!#[0-9]+;)', 'g'), '&amp;');		
		text = text.replace(/</g, '&lt;');
		text = text.replace(/>/g, '&gt;');
		text = text.replace(/"/g, '&quot;');
		return text;
	},	

	fadeIn: function(c, b)
	{
		c = this.$(c);
		clearInterval(c.timer);
		c.style.display = '';
		c.alpha = 0;
		c.timer = setInterval(function(){apadana.changeAlpha(c, +3, b)},6);
	},
	
	fadeOut: function(c, b)
	{
		c = this.$(c);
		clearInterval(c.timer);
		c.style.display = '';
		c.alpha = 100;
		c.timer = setInterval(function(){apadana.changeAlpha(c, -6, b)},6);
	},
	
	changeAlpha: function(c, d, b)
	{
		if (!c.fixalpha){
			c.alpha += d;
			c.style.opacity = c.alpha / 100;
			c.style.filter = 'alpha(opacity=' + c.alpha + ')';
		}
		if (c.alpha <= 0){
			clearInterval(c.timer);
			c.style.display = 'none';
			b&&b();
		}
		else if (c.alpha >= 100){
			clearInterval(c.timer);
			c.style.display = '';
			b&&b();
		}
	},

	bindReady: function(handler)
	{		
		var called = false

		function _ready()
		{
			if (called) return
			called = true
			handler()
		}

		if ( document.addEventListener ) // native event
		{
			document.addEventListener('DOMContentLoaded', _ready, false)
		}
		else if (document.attachEvent) // IE
		{
			try {
				var isFrame = window.frameElement != null
			} catch(e) {}

			// IE, the document is not inside a frame
			if (document.documentElement.doScroll && !isFrame)
			{
				function _tryScroll()
				{
					if (called) return;
					try {
						document.documentElement.doScroll('left');
						_ready()
					} catch(e) {
						setTimeout(_tryScroll, 10)
					}
				}
				_tryScroll()
			}

			// IE, the document is inside a frame
			document.attachEvent('onreadystatechange', function(){
				if (document.readyState === 'complete')
				{
					_ready()
				}
			})
		}

		// Old browsers
		if (window.addEventListener)
		{
			window.addEventListener('load', _ready, false)
		}
		else if (window.attachEvent)
		{
			window.attachEvent('onload', _ready)
		}
		else
		{
			apadana.onload(function(){
				_ready()
			});
		}
	},

	ready: function(handler)
	{
		if (!apadana.readyList)
		{
			apadana.readyList = new Array();
		}
	
		function _executeHandlers()
		{
			for (var i=0; i<apadana.readyList.length; i++)
			{
				apadana.readyList[i]()
			}
		}

		// set handler on first run
		if (!apadana.readyList.length)
		{
			apadana.bindReady(_executeHandlers)
		}

		apadana.readyList.push(handler)
	},
	
	onload: function(func)
	{
		if (!apadana.onloadArray)
		{
			apadana.onloadArray = new Array();
		}

		apadana.onloadArray[apadana.onloadArray.length] = func;
	},

	init: function()
	{	
		if (!apadana.onloadArray)
		{
			apadana.onloadArray = new Array();
		}

		var fn = window.onload // very old browser, copy old onload
		window.onload = function()
		{
			fn && fn()
			for (var i = 0;i < apadana.onloadArray.length;i++)
			{
				if (typeof(apadana.onloadArray[i]) == 'function')
					apadana.onloadArray[i]&&apadana.onloadArray[i]();
				else /*string*/
					eval(apadana.onloadArray[i]);
			}
		}
	}
}

apadana.init();