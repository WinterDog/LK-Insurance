<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;">
	<title>SQL-лог</title>
	<style>
		body					{padding: 30px; margin: 0px; background-color: #F4F5F8; overflow-y: scroll; cursor: default;}
		h1						{color: #22405C; font: 22px Verdana, Tahoma;}
		h3						{color: #22405C; font: 17px Verdana, Tahoma; margin-bottom: 8px;}
		h3 em					{color: #95ABC0; font-style: normal; font: 13px Verdana;}
		h3 em span				{color: #22405C;}
		h3 a					{color: #618ECB; text-decoration: none;}
		h3 a:visited			{color: #618ECB; text-decoration: none;}
		h3 a:hover				{color: #618ECB; text-decoration: underline;}
		h3 a:active				{color: #317BE1; text-decoration: none;}
		hr						{border-top: 1px solid #DDDDDD; border-bottom: 1px solid #FFFFFF; border-left: none; border-right: none;}
		
		.h1_error				{color: #D50000;}
		.h1_info				{color: #0087D5;}
		
		.main_tab td			{padding: 4px 0px; color: #263455; font: normal 11px/18px Verdana; vertical-align: top;}
		.main_tab td a			{color: #618ECB; text-decoration: none;}
		.main_tab td a:visited	{color: #618ECB; text-decoration: none;}
		.main_tab td a:hover	{color: #618ECB; text-decoration: underline;}
		.main_tab td b			{font-family: Tahoma;}
		.main_tab td span		{color: #2A4AB1; font-size: 12px; font-family: 'Courier New'; font-style: normal; margin-bottom: 0px;}
		.wsize					{width: 231px;}
	</style>

	<script type="text/javascript">
		function mysql_log_oc_all(obj, open)
		{
			var inner_a_open, inner_a_close;

			obj.style.display = 'none';

			if (open)
			{
				while (obj = obj.nextSibling)
				{
					if ((obj.nodeType == 1) && (obj.tagName == 'A'))
						break;
				}
			}
			else
			{
				while (obj = obj.previousSibling)
				{
					if ((obj.nodeType == 1) && (obj.tagName == 'A'))
						break;
				}
			}

			obj.style.display = 'inline';

			obj = obj.parentNode;

			while (obj = obj.nextSibling)
			{
				if ((obj.nodeType == 1) && (obj.tagName == 'TABLE') && (obj.style.display))
				{
					inner_a_open = obj.previousSibling.previousSibling.childNodes[1].childNodes[2].childNodes[1].childNodes[1];
					inner_a_close = inner_a_open.nextSibling.nextSibling;

					if (open)
					{
						obj.style.display = 'table';
						inner_a_open.style.display = 'none';
						inner_a_close.style.display = 'inline';
					}
					else
					{
						obj.style.display = 'none';
						inner_a_open.style.display = 'inline';
						inner_a_close.style.display = 'none';
					}
				}
			}
			return true;
		}

		function mysql_log_section_click(obj)
		{
			var i;

			var parent_td = obj.parentNode;
			var parent_tr = parent_td.parentNode;
			var cur_el = parent_tr.parentNode.parentNode;

			// Ищем таблицу, следующую за нашей текущей, в которой находится ссылка, по которой мы ткнули. Короче, таблицу, которую надо сделать видимой.
			while (cur_el = cur_el.nextSibling)
			{
				if ((cur_el.nodeType == 1) && (cur_el.tagName == 'TABLE'))
					break;
			}

			// Раскрываем или скрываем таблицу с подробностями.
			if (cur_el.style.display == 'none')
				cur_el.style.display = 'table';
			else
				cur_el.style.display = 'none';

			// Обрабатываем сслыки.
			for (i in parent_td.childNodes)
			{
				cur_el = parent_td.childNodes[i];
				if ((cur_el.nodeType == 1) && (cur_el.tagName == 'A'))
				{
					if (cur_el.style.display == 'none')
						cur_el.style.display = 'inline';
					else if (cur_el.style.display == 'inline')
						cur_el.style.display = 'none';
				}
			}
			return true;
		}
	</script>
</head>
<body>
	<h3>
		<a href="javascript: this.focus();" style="display: inline;" onclick="mysql_log_oc_all(this, true);">Показать всё &raquo;</a>
		<a href="javascript: this.focus();" style="display: none;" onclick="mysql_log_oc_all(this, false);">Скрыть всё &raquo;</a>
	</h3>
	<hr>
</body>
</html>