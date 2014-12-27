[not-ajax]
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function account_ajax(id)
{
    if (id == 1)
	{
		apadana.ajax({
            method: 'get',
            action: '{admin-page}&module=account',
            data: apadana.serialize('form-options-show'),
            loading: 'no',
            beforeSend: function()
            {
				apadana.html('option-id-1', '<p><center><img src="{site-url}engine/images/loading/loader-9.gif" width="54" height="55"></center></p>');
            },
            success: function(data)
            {
				$('#option-id-1').slideUp('slow', function(){
					$('#option-id-1').html(data).slideDown('slow')
				})
           }
		})
	}
	else if (id == 2)
	{
        apadana.changeTab(1, 2)
        alert('ابتدا یک کاربر را برای ویرایش انتخاب کنید!');
	}
}
function account_list(url)
{
	apadana.ajax({
		method: 'get',
		action: url,
		id: 'option-id-1'
	})
}
function account_edit(ID)
{
	$('#option-ajax-2').slideUp('slow')
	if (ID == 'save')
	{
		ID = apadana.value('account-edit-id');
		apadana.ajax({
			method: 'post',
			action: '{admin-page}&module=account&do=edit',
			data: apadana.serialize('form-edit-account'),
			success: function(data)
			{
				$('#option-ajax-2').slideUp('slow', function(){
					$('#option-ajax-2').html(data).slideDown('slow')
				})
			}
		})
	}
	else
	{
		apadana.changeTab(2, 2);
		apadana.value('account-edit-id', ID);
		apadana.value('account-edit-name', apadana.value('data-account-name-'+ID));
		apadana.value('account-edit-alias', apadana.value('data-account-alias-'+ID));
		apadana.value('account-edit-email', apadana.value('data-account-email-'+ID));
		apadana.value('account-edit-web', apadana.value('data-account-web-'+ID));
		apadana.value('account-edit-signature', apadana.value('data-account-signature-'+ID));
		apadana.value('account-edit-location', apadana.value('data-account-location-'+ID));
		
		if (apadana.value('data-account-gender-'+ID)=='male')
		{
			apadana.$('account-edit-gender-male').checked='checked';
			apadana.$('account-edit-gender-female').checked=false;
		}
		else
		{
			apadana.$('account-edit-gender-male').checked=false;
			apadana.$('account-edit-gender-female').checked='checked';
		}

		if (apadana.value('data-account-newsletter-'+ID)==1)
		{
			apadana.$('account-edit-newsletter-1').checked='checked';
			apadana.$('account-edit-newsletter-0').checked=false;
		}
		else
		{
			apadana.$('account-edit-newsletter-1').checked=false;
			apadana.$('account-edit-newsletter-0').checked='checked';
		}

		for(var i = 0 ; i < apadana.$('account-edit-group').options.length; i++)
		{
			if (apadana.$('account-edit-group').options[i].value == apadana.value('data-account-group-'+ID))
			{
				apadana.$('account-edit-group').options[i].selected = 'selected';
				break;
			}
		}
		
		for(var i = 0 ; i < apadana.$('account-edit-nationality').options.length; i++)
		{
			if (apadana.$('account-edit-nationality').options[i].value == apadana.value('data-account-nationality-'+ID))
			{
				apadana.$('account-edit-nationality').options[i].selected = 'selected';
				break;
			}
		}
	}
}
function account_status(ID)
{
	apadana.ajax({
		method: 'get',
		action: '{admin-page}&module=account&do=status',
		data: 'id='+ID+'&status='+apadana.attr('account-status-'+ID, 'status'),
		success: function(status)
		{
			status = apadana.trim(status);
			if (status == 'ok')
			{
				apadana.changeSrc('account-status-'+ID, '{site-url}engine/images/icons/tick-button.png');
				apadana.attr('account-status-'+ID, 'onmouseover','tooltip.show(\'فعال\')');
				apadana.attr('account-status-'+ID, 'status','1');
			}
			else if (status == 'no')
			{
				apadana.changeSrc('account-status-'+ID, '{site-url}engine/images/icons/minus-button.png');
				apadana.attr('account-status-'+ID, 'onmouseover','tooltip.show(\'اخراج شده\')');
				apadana.attr('account-status-'+ID, 'status','0');
			}
			else
			{
				alert(status);
			}
		}
	})
}
/*]]>*/
</script>

<!-- TAB START-->
<div class="content">
<div class="content-tabs">
<ul>
  <li class="tab-on" id="tab-id-1" onclick="apadana.changeTab(1, 2, function(){account_ajax(1)})">لست کاربران</li>
  <li class="tab-off" id="tab-id-2" onclick="apadana.changeTab(2, 2, function(){account_ajax(2)})">ویرایش کاربر</li>
</ul>
</div>
<div class="content-main">
<div class="content-space">

<div id="option-id-1" style="display:block">
[/not-ajax]
<form id="form-options-show" class="fast-panel">
چینش&nbsp;&raquo;&nbsp;
<select name="order" size="1">
<option value="DESC"[desc] selected="selected"[/desc]>نزولی</option>
<option value="ASC"[asc] selected="selected"[/asc]>صعودی</option>
</select>
&nbsp;&nbsp;تعداد کاربران در صفحه&nbsp;&raquo;&nbsp;
<input name="total" type="text" style="width:25px;text-align:center" value="{total}" maxlength="3" />
&nbsp;&nbsp;جستجو&nbsp;&raquo;&nbsp;
<input name="search" type="text" style="width:150px" value="{search}" dir="ltr" />
[pages]
&nbsp;&nbsp;صفحه&nbsp;&raquo;&nbsp;
<select name="page" size="1">
[for pages]<option value="{number}"[selected] selected="selected"[/selected]>{number}</option>[/for pages]
</select>
[/pages]
&nbsp;&nbsp;
<input type="button" value="نمایش" onclick="account_list('{admin-page}&module=account&'+apadana.serialize('form-options-show'))" />
<input name="sort" type="hidden" value="{sort}" />
</form>
[members]
<table class="apadana-table" cellpadding="0" cellspacing="0">
<thead>
  <tr>
	<th width="25"># [member-id]<a onmouseover="tooltip.show('چینش بر اساس آی دی کاربر')" onmouseout="tooltip.hide()" href="javascript:void(0)" onClick="tooltip.hide();account_list('{admin-page}&module=account&sort=member_id&order={order}&total={total}&search={search}&page={page}')">^</a>[/member-id]</th>
	<th align="right">نام کاربری [member-name]<a onmouseover="tooltip.show('چینش بر اساس نام کاربری')" onmouseout="tooltip.hide()" href="javascript:void(0)" onClick="tooltip.hide();account_list('{admin-page}&module=account&sort=member_name&order={order}&total={total}&search={search}&page={page}')">^</a>[/member-name]</th>
	<th width="20">پروفایل</th>
	<th width="20">وضعیت</th>
	<th width="60">جنسیت [member-gender]<a onmouseover="tooltip.show('چینش بر اساس جنسیت')" onmouseout="tooltip.hide()" href="javascript:void(0)" onClick="tooltip.hide();account_list('{admin-page}&module=account&sort=member_gender&order={order}&total={total}&search={search}&page={page}')">^</a>[/member-gender]</th>
	<th width="60">بازدیدها [member-visits]<a onmouseover="tooltip.show('چینش بر اساس بازدیدها')" onmouseout="tooltip.hide()" href="javascript:void(0)" onClick="tooltip.hide();account_list('{admin-page}&module=account&sort=member_visits&order={order}&total={total}&search={search}&page={page}')">^</a>[/member-visits]</th>
	<th width="140">آخرین بازدید [member-lastvisit]<a onmouseover="tooltip.show('چینش بر اساس آخرین بازدید')" onmouseout="tooltip.hide()" href="javascript:void(0)" onClick="tooltip.hide();account_list('{admin-page}&module=account&sort=member_lastvisit&order={order}&total={total}&search={search}&page={page}')">^</a>[/member-lastvisit]</th>
	<th width="20">عملیات</th>
  </tr>
</thead>
<tbody>[for members]
  <tr class="{odd-even}">
	<td>{id}
	<div style="display:none">
	<input id="data-account-id-{id}" type="hidden" value="{id}" />
	<input id="data-account-name-{id}" type="hidden" value="{name}" />
	<input id="data-account-visits-{id}" type="hidden" value="{visits}" />
	<input id="data-account-lastvisit-{id}" type="hidden" value="{lastvisit}" />
	<input id="data-account-status-{id}" type="hidden" value="{status}" />
	<input id="data-account-email-{id}" type="hidden" value="{email}" />
	<input id="data-account-ip-{id}" type="hidden" value="{ip}" />
	<input id="data-account-lastip-{id}" type="hidden" value="{lastip}" />
	<input id="data-account-web-{id}" type="hidden" value="{web}" />
	<input id="data-account-alias-{id}" type="hidden" value="{alias}" />
	<input id="data-account-group-{id}" type="hidden" value="{group}" />
	<input id="data-account-newsletter-{id}" type="hidden" value="{newsletter}" />
	<input id="data-account-nationality-{id}" type="hidden" value="{nationality}" />
	<input id="data-account-location-{id}" type="hidden" value="{location}" />
	<input id="data-account-gender-{id}" type="hidden" value="{gender}" />
	<textarea id="data-account-signature-{id}">{signature}</textarea>
	</div>
	</td>
	<td align="right">{name-show}</td>
	<td><a href="{url}" target="_blank"><img src="{site-url}engine/images/icons/cursor.png" width="16" height="16" onmouseover="tooltip.show('مشاهده پروفایل کاربر')" onmouseout="tooltip.hide()"></a></td>
	<td><a href="javascript:account_status({id})"><img src="{site-url}engine/images/icons/[status]tick-button[/status][not-status]minus-button[/not-status].png" width="16" height="16" onmouseover="tooltip.show('[status]فعال[/status][not-status]اخراج شده[/not-status]')" onmouseout="tooltip.hide()" id="account-status-{id}" status="{status}"></a></td>
	<td><img src="{site-url}engine/images/icons/gender[not-gender-male]-female[/not-gender-male].png" onmouseover="tooltip.show('[gender-male]مرد[/gender-male][not-gender-male]زن[/not-gender-male]')" onmouseout="tooltip.hide()" /></td>
	<td>{visits}</td>
	<td>{lastvisit-show}</td>
	<td><a href="javascript:account_edit({id})"><img src="{site-url}engine/images/icons/document-edit-icon.png" width="16" height="16" onmouseover="tooltip.show('ویرایش')" onmouseout="tooltip.hide()"></a></td>
  </tr>
[/for members]
</tbody>
</table>
[/members]
[not-members]{function name="message" args="برای جستجوی <u>{search}</u> هیچ نتیجه ای یافت نشد!|error"}[/not-members]
[not-ajax]
</div>
<!-- /option-id-1 -->
<div id="option-id-2" style="display:none">
<div id="option-ajax-2"></div>
<form id="form-edit-account" onsubmit="account_edit('save');return false">
<table cellpadding="6" cellspacing="0">
  <tr>
	<td width="100">نام کاربری</td>
	<td><input type="text" size="30" value="" disabled="disabled" dir="ltr" id="account-edit-name" /></td>
  </tr>
  <tr>
	<td>گروه کاربری</td>
	<td><select name="member[group]" size="1" id="account-edit-group" style="border: 1px red solid">[for groups]<option value="{id}">{name}</option>[/for groups]</select></td>
  </tr>
  <tr>
	<td>نام مستعار</td>
	<td><input name="member[alias]" type="text" size="30" value="" id="account-edit-alias" /></td>
  </tr>
  <tr>
	<td>ملیت</td>
	<td><select name="member[nationality]" size="1" dir="ltr" id="account-edit-nationality"><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Territory">British Indian Ocean Territory</option><option value="Brunei Darussalam">Brunei Darussalam</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Cook Islands">Cook Islands</option><option value="Costa Rica">Costa Rica</option><option value="Cote D'Ivoire">Cote D'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor">East Timor</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="France, Metropolitan">France, Metropolitan</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Territories">French Southern Territories</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-bissau">Guinea-bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard and Mc Donald Islands">Heard and Mc Donald Islands</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran (Islamic Republic of)" selected="selected">Iran (Islamic Republic of)</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option><option value="Korea, Republic of">Korea, Republic of</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macau">Macau</option><option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia, Federated States of">Micronesia, Federated States of</option><option value="Moldova, Republic of">Moldova, Republic of</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn">Pitcairn</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russian Federation">Russian Federation</option><option value="Rwanda">Rwanda</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia (Slovak Republic)">Slovakia (Slovak Republic)</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="St. Helena">St. Helena</option><option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syrian Arab Republic">Syrian Arab Republic</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania, United Republic of">Tanzania, United Republic of</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option><option value="Venezuela">Venezuela</option><option value="Viet Nam">Viet Nam</option><option value="Virgin Islands (British)">Virgin Islands (British)</option><option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option><option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Serbia">Serbia</option><option value="The Democratic Republic of Congo">The Democratic Republic of Congo</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option><option value="Jersey">Jersey</option><option value="St. Barthelemy">St. Barthelemy</option><option value="St. Eustatius">St. Eustatius</option><option value="Canary Islands">Canary Islands</option><option value="Montenegro">Montenegro</option></select></td>
  </tr>
  <tr>
	<td>محل زندگی</td>
	<td><input name="member[location]" type="text" size="30" value="" id="account-edit-location" /></td>
  </tr>
  <tr>
	<td>جنسیت</td>
	<td><label><input type="radio" name="member[gender]" value="male" checked="checked" id="account-edit-gender-male" />مرد</label> <label><input type="radio" name="member[gender]" value="female" id="account-edit-gender-female" />زن</label> </td>
  </tr>
  <tr>
	<td>ایمیل</td>
	<td><input name="member[email]" type="text" size="30" value="" id="account-edit-email" dir="ltr" /></td>
  </tr>
  <tr>
	<td>وب سایت</td>
	<td><input name="member[web]" type="text" size="30" value="" id="account-edit-web" dir="ltr" /></td>
  </tr>
  <tr>
	<td>عضویت در خبرنامه اختصاصی اعضا</td>
	<td><label><input type="radio" name="member[newsletter]" value="1" checked="checked" id="account-edit-newsletter-1" />بله</label> <label><input type="radio" name="member[newsletter]" value="0" id="account-edit-newsletter-0" />خیر</label> </td>
  </tr>
  <tr>
	<td>امضا</td>
	<td><textarea name="member[signature]" cols="50" rows="7" id="account-edit-signature"></textarea></td>
  </tr>
  <tr>
	<td></td>
	<td>در صورتی که نمی خواهید پسورد کاربر را تغییر دهید فیلدهای آن را خالی رها کنید.</td>
  </tr>
  <tr>
	<td>پسورد</td>
	<td><input name="member[pass1]" type="password" size="30" value="" dir="ltr" /></td>
  </tr>
  <tr>
	<td>تکرار پسورد</td>
	<td><input name="member[pass2]" type="password" size="30" value="" dir="ltr" /></td>
  </tr>
  <tr>
	<td></td>
	<td><input id="account-edit-id" name="member[id]" type="hidden" /><input type="submit" value="ویرایش کاربر" /></td>
  </tr>
</table>
</form>
</div>
<!-- /option-id-2 -->

<div class="clear"></div>
</div>
</div>
<div class="content-bottom"></div>
</div>
<!-- TAB END-->
[/not-ajax]