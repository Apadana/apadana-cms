<script type="text/javascript">
var fileEdit = '';
function templates_load()
{
	fileEdit = apadana.value('template-file');
	apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=templates&do=edit&name={template}',
        data: 'file='+fileEdit,
        success: function(data)
        {
            if(data == '{error}')
            	alert('فایل انتخابی شما معتبر نیست!');
            else
			{
    			apadana.html('template-file-name', fileEdit);
    			apadana.html('template-button', 'ویرایش فایل '+fileEdit);
    			apadana.value('template-contents', data);
    			apadana.showID('template-form');
                editor.setOption("value", data);
                change(fileEdit);
			}
        }
    });
}
function templates_new()
{
	apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=templates&do=edit&name={template}',
        data: 'new='+apadana.value('template-file-new'),
        success: function(data)
        {
            data = apadana.trim(data);
            if(data == 'OK')
				apadana.location("{admin-page}&section=templates&do=edit&name={template}");
			else
				alert(data);
        }
    });
}
function templates_edit()
{
	if(fileEdit == '')
	{
		return alert('ابتدا یک فایل را برای ویرایش انتخاب کنید');
	}

	apadana.ajax({
        type: 'POST',
        action: '{admin-page}&section=templates&do=edit&name={template}',
        data: 'file='+fileEdit+'&contents='+encodeURIComponent(editor.getValue()),
        success: function(data)
        {
            data = apadana.trim(data);
			if(data == '{error}')
			{
            	alert('فایل انتخابی شما معتبر نیست!');
			}
            else if(data != '')
			{
				alert(data);
			}
        }
    });
}

</script>

<div align="center" style="margin:10px 0px">
<select style="width:400px;margin-bottom:10px" dir="ltr" id="template-file">[for files]<option value="{file}">{file}</option>[/for files]</select>
<button onclick="templates_load()">ویرایش فایل</button>
</div>
<div align="center" style="margin:10px 0px">
<input style="width:330px;margin-bottom:10px" dir="ltr" id="template-file-new" type="text">
<button onclick="templates_new()">ساختن فایل یا پوشه جدید</button>
</div>

<div id="template-form" style="display:none;">
<p style="margin: 10px 0px">در حال ویرایش فایل <b dir="ltr" id="template-file-name"></b>:                  نوع فایل :<span dir="ltr" id="modeinfo"></span></p>
<div class="editor" dir="ltr" style="text-align:left;" >
    <textarea name="g" id="template-contents" style=""></textarea>
</div>
<center><button onclick="templates_edit()" id="template-button" style="margin:5px 0px">ویرایش فایل</button></center>
</div>
<script>
    
    //CodeMirror Options

CodeMirror.modeURL = "{site-url}/engine/javascript/codemirror/mode/%N/%N.js";
var editor = CodeMirror.fromTextArea(document.getElementById("template-contents"), {
  lineNumbers: true,
  keyMap: "sublime",
  autoCloseBrackets: true,
  matchBrackets: true,
  showCursorWhenSelecting: true,
  theme: "monokai",
  indentUnit: 4,
  extraKeys: {
      "F11": function(cm) {
        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
      },
      "Esc": function(cm) {
        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
      }
    }
});
function change(val) {
  var m, mode, spec;
  if (m = /.+\.([^.]+)$/.exec(val)) {
    m[1] == 'tpl' ? m[1] = 'html' : null;
    var info = CodeMirror.findModeByExtension(m[1]);
    if (info) {
      mode = info.mode;
      spec = info.mime;
    }
  } else if (/\//.test(val)) {
    var info = CodeMirror.findModeByMIME(val);
    if (info) {
      mode = info.mode;
      spec = val;
    }
  } else {
    mode = spec = val;
  }
  if (mode) {
    editor.setOption("mode", spec);
    CodeMirror.autoLoadMode(editor, mode);
    document.getElementById("modeinfo").textContent = spec;
  } else {
    alert("Could not find a mode corresponding to " + val);
  }
}
</script>