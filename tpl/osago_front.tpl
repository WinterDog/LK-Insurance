{extends "classes/content.tpl"}

{block "header_bgr_class"}bgr-car-c{/block}
{block "header_class"}{/block}

{block "header_block_text"}
	<a class="btn btn-warning margin-t" href="/osago_calculator/" role="button">Калькулятор ОСАГО</a>
{/block}

{block "content" append}

	{$_PAGE->content}

	<a class="btn btn-primary margin-t" href="/osago_calculator/" role="button">Калькулятор ОСАГО</a>

{/block}