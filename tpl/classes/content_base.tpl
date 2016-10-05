{extends "classes/body.tpl"}

{block "content_base"}
	<div class="header-bgr {*block "header_bgr_class"*}bgr-common-blue{*/block*}">
		<div class="container-fluid content-wrap-h {block "header_width_class"}max-width-md{/block}">
			<div class="header-bgr-title">
				<h1>{block "header_title"}{/block}</h1>
			</div>
		</div>
		{block "header_block"}
			{include "inc/system/header-block.tpl"}
		{/block}
	</div>

	{if (sizeof($_PAGE->parents) > 0)}
		{block "breadcrumb"}
			<div class="container-fluid hidden-xs breadcrumb-wrap content-wrap-h {block "header_width_class"}max-width-md{/block}">
				<ol class="breadcrumb">
					{foreach $_PAGE->parents as $parent_page}
						<li><a href="/{$parent_page->name}/">{$parent_page->title}</a></li>
					{/foreach}
					<li class="active">{$_PAGE->title}</li>
				</ol>
			</div>
		{/block}
	{/if}

	{block "content_wrap"}
	{/block}
{/block}