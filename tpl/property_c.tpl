{extends "classes/content.tpl"}

{block "header_bgr_class"}bgr-property-c{/block}
{block "header_class"}{/block}

{block "header_block_text"}
	<p>
	</p>
	<a class="btn btn-block btn-lg btn-warning" href="/property_calc_c/?property_type=1" role="button">
		Калькулятор по квартире
	</a>
	<p>
	</p>
	<a class="btn btn-block btn-lg btn-warning margin-t" href="/property_calc_c/?property_type=2" role="button">
		Калькулятор по дому
	</a>
{/block}

{block "content" append}

	{$_PAGE->content}

{/block}