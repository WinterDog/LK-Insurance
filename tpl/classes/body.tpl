	<div hidden id="meta-div">
		<div id="meta-page-type">{block "meta_page_type"}article{/block}</div>
		<div id="meta-title">{block "meta_title"}{$_META['title']}{/block}</div>
		<div id="meta-description">{block "meta_description"}{$_META['description']}{/block}</div>
		<div id="meta-image">{block "meta_image"}{$_META['image']}{/block}</div>
		<div id="meta-keywords">{block "meta_keywords"}{$_PAGE->meta_keywords}{/block}</div>
		{* <div id="meta-bgr">/css/img/bgr/{$_PAGE->bgr_image_src}</div> *}
	</div>

	{include "inc/system/header.tpl"}
	{include "inc/system/main-menu.tpl"}
	{if ($_CFG['debug'])}
		{include "inc/system/debug_bar.tpl"}
	{/if}

	{block "content_base"}
	{/block}