<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function category_ajax(id)
{
    if (id == 1)
	{
		apadana.html('option-ajax-1', '');
		apadana.$('form-new-categories').reset();
		$('#form-new-categories:hidden').slideDown('slow');

		apadana.ajax({
			method: 'get',
			action: '{admin-page}&module=posts&do=categories-parent',
			loading: 'no',
			beforeSend: function()
			{
				apadana.html('categories-parent-ajax', '<img src="{site-url}engine/images/loading/loader-16.gif">');
			},
			success: function(data)
			{
				apadana.html('categories-parent-ajax', data);
			}
		})
	}
	else if (id == 2)
	{
		apadana.ajax({
            method: 'get',
            action: '{admin-page}&module=posts&do=categories-list',
            loading: 'no',
            beforeSend: function()
            {
			    apadana.html('option-id-2', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55" /></center></p>');
            },
            success: function(data)
            {
				$('#option-id-2').fadeOut('slow', function(){
					$('#option-id-2').html(data).fadeIn('slow')
				})
            }
		})
	}
	else if (id == 3)
	{
        apadana.changeTab(2, 3, function(){category_ajax(2)})
        alert('ابتدا یک موضوع را برای ویرایش انتخاب کنید!');
	}
}
function category_new()
{
	$('#option-ajax-1').slideUp('slow')
	apadana.ajax({
		method: 'post',
		action: '{admin-page}&module=posts&do=categories-new',
		data: apadana.serialize('form-new-categories'),
		success: function(data)
		{
			$('#option-ajax-1').slideUp('slow', function(){
				$('#option-ajax-1').html(data).slideDown('slow')
			})
		}
	})
}
function category_edit(ID)
{
	$('#option-ajax-3').slideUp('slow')
    if (ID == 'save')
    {
		ID = apadana.value('categories-id-edit');
		apadana.ajax({
            method: 'post',
            action: '{admin-page}&module=posts&do=categories-edit&id='+ID,
            data: apadana.serialize('form-edit-categories'),
            success: function(data)
            {
				$('#option-ajax-3').slideUp('slow', function(){
					$('#option-ajax-3').html(data).slideDown('slow')
				})
            }
		})
    }
    else
    {
        apadana.changeTab(3, 3)
		apadana.value('categories-id-edit', apadana.value('data-categories-id-'+ID));
		apadana.value('categories-name-edit', apadana.value('data-categories-name-'+ID));
		apadana.value('categories-slug-edit', apadana.value('data-categories-slug-'+ID));
		apadana.value('categories-description-edit', apadana.value('data-categories-description-'+ID));

		if (apadana.value('data-categories-parent-'+ID) == 0)
		{
			apadana.showID('categories-info-edit');
		}
		else
		{
			apadana.hideID('categories-info-edit');
		}

		apadana.ajax({
            method: 'get',
            action: '{admin-page}&module=posts&do=categories-parent&edit=true',
            loading: 'no',
            beforeSend: function()
            {
				apadana.html('categories-parent-ajax-edit', '<img src="{site-url}engine/images/loading/loader-16.gif">');
            },
            success: function(data)
            {
				apadana.html('categories-parent-ajax-edit', data);
				for (var i = 0 ; i < apadana.$('categories-parent-edit').options.length; i++)
				{
					if (apadana.$('categories-parent-edit').options[i].value == apadana.value('data-categories-parent-'+ID))
					{
						apadana.$('categories-parent-edit').options[i].selected = 'selected';
						break;
					}
				}
            }
		})
    }
}
function category_delete(ID, parent)
{
	if (parent == 1 && !confirm("آیا از حذف این موضوع اطمینان دارید؟\n موضوعات زیر مجموعه این موضوع خود سردسته خواهند شد!")) return false;
	if (parent != 1 && !confirm('آیا از حذف این موضوع اطمینان دارید؟')) return false;

	apadana.ajax({
		method: 'get',
		action: '{admin-page}&module=posts&do=categories-delete',
		data: 'id='+ID,
		success: function(data)
		{
			$('#option-id-2').fadeOut('slow', function(){
				$('#option-id-2').html(data).fadeIn('slow')
			})
		}
	})
}
function category_slug(ID, ID2)
{
	text = apadana.value(ID);
	text = apadana.slug(text);

	if (text == '')
	{
		alert('عنوان موضوع را ننوشته اید!');
		return;
	}

	apadana.value(ID2, text);
}
$(document).ready(function(){category_ajax(1)});
/*]]>*/
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 3, function(){category_ajax(1)})">موضوع جدید</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 3, function(){category_ajax(2)})">مدیریت موضوع ها</li>
  <li class="tab-off" id="tab-id-3" onclick="apadana.changeTab(3, 3, function(){category_ajax(3)})">ویرایش موضوع</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
<div id="option-ajax-1"></div>
<form id="form-new-categories" onsubmit="category_new();return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="90">عنوان</td>
	<td><input id="categories-name" name="categories[name]" type="text" size="50" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="categories-slug" name="categories[slug]" type="text" size="50" dir="ltr" />&nbsp;<input type="button" value="بساز" onclick="category_slug('categories-name', 'categories-slug')" /></td>
  </tr>
  <tr>
	<td>توضیحات</td>
	<td><textarea id="categories-description" name="categories[description]" cols="80" rows="5" lang="fa-ir" /></textarea></td>
  </tr>
  <tr>
	<td>سردسته</td>
	<td><div id="categories-parent-ajax"><img src="{site-url}engine/images/loading/loader-16.gif"></div></td>
  </tr>
  <tr>
	<td></td>
	<td><input type="submit" name="submit" value="ذخیره موضوع" /> &nbsp; <input type="reset" value="از نو" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none"></div>
<!-- /option-id-2 -->
<div id="option-id-3" style="display:none">
<div id="option-ajax-3"></div>
<form id="form-edit-categories" onsubmit="category_edit('save');return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="90">عنوان</td>
	<td><input id="categories-name-edit" name="categories[name]" type="text" size="50" lang="fa-ir" /></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input id="categories-slug-edit" name="categories[slug]" type="text" size="50" dir="ltr" />&nbsp;<input type="button" value="بساز" onclick="category_slug('categories-name-edit', 'categories-slug-edit')" /></td>
  </tr>
  <tr>
	<td>توضیحات</td>
	<td><textarea id="categories-description-edit" name="categories[description]" cols="80" rows="5" lang="fa-ir" /></textarea></td>
  </tr>
  <tr>
	<td>سردسته</td>
	<td><div id="categories-parent-ajax-edit"></div></td>
  </tr>
  <tr>
	<td></td>
	<td><div id="categories-info-edit">این موضوع خود سردسته است با تغییر آن به یک زیر مجموعه، تمام موضوعات زیر مجموعه آن به موضوع سردسته جدید منتقل خواهند شد.</div></td>
  </tr>
  <tr>
	<td></td>
	<td><input id="categories-id-edit" type="hidden" /><input type="submit" name="submit" value="ویرایش موضوع" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-3 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
