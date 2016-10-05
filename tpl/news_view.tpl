{extends "classes/content.tpl"}

{block "header_title"}Новости{/block}

{block "content" append}

	<article>
		<header>
			{*<strong>{$article->create_date_s}</strong> — *}

			<h2>{$article->title}</h2>
			<h3>{$article->content_cut}</h3>
		</header>

		{$article->content}

		<hr>

		<footer class="margin-t">
			{if (sizeof($article->tags) > 0)}
				<p class="text-muted">
					<small>
						Ключевые слова:
						{foreach $article->tags as $tag}
							<a href="/news/?tag={$tag->id}">{$tag->title}</a>{if (!$tag@last)},{/if}
						{/foreach}
					</small>
				</p>
			{/if}

			{if ($article->source_title != '')}
				<div class="text-muted margin-t-lg">
					Источник: <a href="{$article->source_url}">{$article->source_title}</a>
				</div>
			{/if}

			{include "inc/system/share.tpl"}

			{*
			<div class="share-btn-wrap" id="share-btn-wrap">
				<div class="share-btn" id="share-vk"></div>
				<script>
					$(function ()
					{
						$('#share-vk').html(VK.Share.button(
						{
							description:	'{$article->content_cut}',
							image:			'{$_CFG['contacts']['url']}{$article->main_image}',
							noparse:		true,
							title:			'{$article->title}',
							url:			'{$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}',
						},
						{
							text:			'Поделиться',
							type:			'round',
						}));
					});
				</script>

				<div class="share-btn">
					<div class="fb-share-button" data-href="{$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}" data-layout="button"></div>
				</div>
			</div>
			*}
		</footer>

		{include "inc/system/disqus_init.tpl"}

		<a class="btn btn-default margin-t-lg" href="javascript:;" role="button" onclick="GoBack();">&laquo; Назад</a>

		{if ($_PAGES['article_edit']->rights > 0)}
			<div class="margin-tb-lg">
				<a class="btn btn-primary" href="/article_edit/?id={$article->id}" role="button">
					<span class="fa fa-pencil"></span>
					Редактировать
				</a>
				<button class="btn btn-danger" type="button" onclick="RefItemDeleteForm({$article->id});">
					<span class="fa fa-times"></span>
					Удалить
				</button>
			</div>
		{/if}
	</article>

	{if ($_PAGES['article_edit']->rights > 0)}
		{include "inc/modal_ref_dialog.tpl" page_name='article_edit'}
	{/if}

{/block}