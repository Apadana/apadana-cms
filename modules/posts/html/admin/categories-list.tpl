[categories]
<table class="apadana-table" cellpadding="0" cellspacing="0">
  <tr>
    <th width="5">#</th>
    <th align="right">عنوان موضوع</th>
    <th width="200">سردسته</th>
    <th width="20">عملیات</th>
  </tr>[for categories]
  <div style="display:none">
  <input id="data-categories-id-{id}" type="hidden" value="{id}" />
  <input id="data-categories-name-{id}" type="hidden" value="{name}" />
  <input id="data-categories-parent-{id}" type="hidden" value="{parent}" />
  <input id="data-categories-slug-{id}" type="hidden" value="{slug}" />
  <textarea id="data-categories-description-{id}">{description}</textarea>
  </div>
  <tr class="{odd-even}">
    <td>{id}</td>
    <td align="right">[child]{depth}{name}[/child][not-child]<b>{depth}{name}</b>[/not-child]</td>
    <td>{parent-name}</td>
    <td><a href="javascript:void(0)" onclick="category_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()"></a>&nbsp;<a href="javascript:void(0)" onclick="category_delete({id}, [child]0[/child][not-child]1[/not-child])"><img src="{site-url}engine/images/icons/cross-script.png" width="16" height="16" onmouseover="tooltip.show('حذف')" onmouseout="tooltip.hide()"></a></td>
  </tr>[/for categories]
</table>
[/categories]
[not-categories]{function name="message" args="هیچ موضوعی ساخته نشده است!|info"}[/not-categories]