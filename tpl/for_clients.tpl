{extends "classes/content.tpl"}

{block "header_bgr_class"}bgr-front-page-c{/block}
{block "header_class"}{/block}

{block "header_block"}
	{include "inc/system/header-block.tpl"}
{/block}

{block "content" append}

	{$_PAGE->content}

{/block}