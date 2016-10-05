{extends "classes/content_base.tpl"}

{block "content_base"}

	{include "inc/header-bgr.tpl"}

	{block "content_wrap"}

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

{/block}