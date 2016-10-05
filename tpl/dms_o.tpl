{extends "classes/content.tpl"}

{* block "header_block"}
	{block "header_block_text"}
		<p>
			Отправьте нам заявку на расчёт ДМС прямо сейчас!
			В течение 24 часов мы подготовим несколько вариантов, наилучшим образом подходящих вашей компании.
			Вам останется лишь выбрать - остальным займёмся мы.
		</p>
		<a class="btn btn-block btn-lg btn-warning" href="/dms_query_o/" role="button">
			Заявка на расчёт ДМС
		</a>
	{/block}
{/block *}

{block "content" append}

	<div class="clearfix">
		{$_PAGE->content}
	</div>

	<a class="btn btn-lg btn-warning margin-t-lg" href="/dms_query_o/" role="button">
		Калькулятор / заявка на расчёт
	</a>

{/block}