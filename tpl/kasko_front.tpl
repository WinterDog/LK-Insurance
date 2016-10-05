{extends "classes/content.tpl"}

{block "header_bgr_class"}bgr-car-c{/block}
{block "header_class"}{/block}

{block "header_block"}
	<a class="btn btn-warning margin-t" href="/kasko_query/" role="button">Заявка на расчёт стоимости</a>
{/block}

{block "content" append}

	{$_PAGE->content}

	<a class="btn btn-primary margin-t" href="/kasko_query/" role="button">Заявка на расчёт стоимости</a>

{/block}