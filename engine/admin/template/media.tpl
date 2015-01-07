<!-- jQuery and jQuery UI (REQUIRED) -->
<link rel="stylesheet" type="text/css" media="screen" href="engine/elfinder/js/jquery-ui-1.8/base/jquery.ui.all.css">
<script type="text/javascript" src="engine/javascript/jquery-1.7.2.js"></script>
<script type="text/javascript" src="engine/elfinder/js/jquery-ui-1.8/jquery-ui-1.8.21.custom.min.js"></script>

<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" type="text/css" media="screen" href="engine/elfinder/css/elfinder.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="engine/elfinder/css/theme.css">

<!-- elFinder JS (REQUIRED) -->
<script type="text/javascript" src="engine/elfinder/js/elfinder.min.js"></script>

<!-- elFinder translation (OPTIONAL) -->
<script type="text/javascript" src="engine/elfinder/js/i18n/elfinder.LANG.js"></script>

<!-- elFinder initialization (REQUIRED) -->
<script type="text/javascript" charset="utf-8">
	$().ready(function() {
[no-template]
		var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");
		var langCode = window.location.search.replace(/^.*langCode=([a-z]{2}).*$/, "$1");

		$('#elfinder').elfinder({
			url : '{admin-page}&section=media&connector=true',
			height: '650', 
			//lang : langCode,
			getFileCallback : function(url) {
			[editor]
				window.opener.CKEDITOR.tools.callFunction(funcNum, url);
				window.close();
			[/editor]
			[input]
				window.opener.apadana.$('{input}').value = url;
				window.close();
			[/input]
			}
		});
[/no-template]
[template]
		$('#elfinder').elfinder({
			url : '{admin-page}&section=media&connector=true',  // connector URL (REQUIRED)
			height: '650', 
			//lang: 'en', // language (OPTIONAL)
		}).elfinder('instance');
		$('#footer').css('display','none');
[/template]
	});
</script>

<!-- Element where elFinder will be created (REQUIRED) -->
<div dir="ltr" style="text-align:left">
<div id="elfinder"></div>
</div>