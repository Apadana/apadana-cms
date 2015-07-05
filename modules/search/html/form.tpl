[message]{message}
[/message]<form action="{a href='search/result'}" method="get">
[not-rewrite]<input type="hidden" name="a" value="search" /><input type="hidden" name="b" value="result" />
[/not-rewrite]<table cellpadding="0" cellspacing="0" width="100%" id="module-search">
    <tr>
        <td>
            <div align="center">
                <table cellpadding="0" cellspacing="8" width="100%">
                    <tr style="vertical-align: top;">
                        <td>
                            <fieldset style="padding-top:5px">
                                <legend>جستجو در مطالب</legend>
                                <table cellpadding="0" cellspacing="3" border="0" width="100%">
                                    <tr><td style="padding-bottom:5px">کلمات کلیدی <font color="red">*</font><div><input type="text" name="story" value="{story}" style="width:98%" /></div></td></tr>
                                    <tr><td><select name="type" size="1" style="width:100%"><option value="0" style="font-weight: bold;"[type-title] selected="selected"[/type-title]>جستجو در عناوین</option><option value="1"[type-content] selected="selected"[/type-content]>جستجو در محتوا</option><option value="2"[type-content&title] selected="selected"[/type-content&title]>جستجو در عنوان و محتوا</option></select>
                                    </td></tr>
                                </table>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding-bottom:14px">
                                <legend>جستجو بر اساس نام کاربری</legend>
                                نام کاربری
                                <div style="padding-bottom:7px"><input type="text" name="author" value="{author}" style="width:98%" dir="ltr" /></div>
                                <label><input type="checkbox" name="author-full" value="1"[author-full] checked="checked"[/author-full] />&nbsp;جستجوی دقیق براساس نام کاربری</label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <td width="50%" valign="top">
                        <fieldset style="padding-top:5px">
                            <legend>مرتب سازی نتایج</legend>
                            <div style="padding:3px">
                                <select name="sortby" size="1"><option value=""[sortby-id] selected="selected"[/sortby-id]>هیچکدام</option><option value="date"[sortby-date] selected="selected"[/sortby-date]>تاریخ مقالات</option><option value="title"[sortby-title] selected="selected"[/sortby-title]>عنوان مقالات</option><option value="author"[sortby-author] selected="selected"[/sortby-author]>نام نویسنده</option></select>
                                <div style="padding-bottom:5px"></div>
                                <select name="sort-type" size="1"><option value="desc"[sort-desc] selected="selected"[/sort-desc]>نزولی</option><option value="asc"[sort-asc] selected="selected"[/sort-asc]>صعودی</option></select>
                            </div>
                        </fieldset>
                        <fieldset style="padding-top:5px">
                            <legend>نمایش نتایج</legend>
                            <table cellpadding="0" cellspacing="3" border="0">
                                <tr valign="middle">
                                    <td width="140"><span>تعداد نتایج: </span></td><td><input type="text" name="result-in-page" value="{result-in-page}" maxlength="4" /></td>
                                </tr>
                                <tr valign="middle">
                                    <td>نمایش نتایج: </td>
                                    <td style="padding-top: 5px;"><label><input type="radio" name="view-type" value="0"[view-title] checked="checked"[/view-title] /> عناوین</label><br /><label><input type="radio" name="view-type" value="1"[view-content] checked="checked"[/view-content] /> خلاصه مقالات</label></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                        <td width="50%" valign="top">
                            <fieldset>
                                <legend>بخش های مورد جستجو</legend>
                                <div style="padding: 6px 4px 9px 4px;"><div><select name="modules[]" multiple="multiple" size="8" style="width:99%">[for modules]<option value="{name}"[selected] selected="selected"[/selected]>{title}</option>[/for modules]</select></div></div>
                                <label><input type="checkbox" name="all-modules" value="1"[all-modules] checked="checked"[/all-modules] />&nbsp;جستجو در تمام بخش ها</label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div align="center">
                                <input type="submit" style="margin:10px 10px 0 0px" value="جستجو در سایت" />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</form>