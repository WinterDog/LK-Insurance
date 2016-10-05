{extends "classes/content_base.tpl"}

{block "header_title"}{$_PAGE->title}{/block}
{block "header_block"}{/block}

{block "content_wrap" append}

	<div class="container-fluid content-wrap-h {block "header_width_class"}max-width-md{/block}">

		<div class="content content-wrap-v">
			{block "content"}
			{/block}
		</div>

	</div>

{/block}