{extends "classes/content_base.tpl"}

{block "header_width_class"}max-width-lg{/block}

{block "header_title"}{/block}

{block "content_wrap" append}

	<div class="container-fluid content-wrap-h max-width-lg">

		<div class="content content-wrap-v">
			{block "content"}
				{block "content_h1"}
					<h1>{block "content_title"}{$_PAGE->title}{/block}</h1>
				{/block}
			{/block}
		</div>

	</div>

{/block}