{extends "classes/content.tpl"}

{block "header_bgr_class"}bgr-car-o{/block}
{block "header_class"}{/block}

{block "header_block_class"}header-block-r{/block}
{block "header_block_text"}
	<p>
		Заявка на расчёт стоимости КАСКО - варианты по ведущим страховым компаниям, ответ в течение 24 часов.
	</p>
	<a class="btn btn-block btn-lg btn-warning margin-t" href="/kasko_query_o/" role="button">Заявка на расчёт КАСКО</a>
{/block}

{block "content" append}

	<div class="clearfix margin-b-lg">
		{$_PAGE->content}
	</div>

	<a class="btn btn-lg btn-primary" href="/kasko_query_o/" role="button">Заявка на расчёт КАСКО</a>

{/block}