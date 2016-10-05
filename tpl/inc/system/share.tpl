	{*if (sizeof($article->tags) > 0)}
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
	{/if*}

	<h6 class="share-label">
		Поделиться
	</h6>
	<div class="clearfix share-btn-wrap" role="list">
		{*
		<a
			class="btn btn-default"
			href="https://www.facebook.com/plugins/like.php?href={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}"
			role="listitem"
			target="_blank"
			title="Like On Facebook"
		>
			<span class="fa fa-thumbs-o-up fb"></span>
		</a>
		*}
		
		<a
			class="vk"
			href="https://vk.com/share.php?url={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}&title={$article->title}&description={$article->content_cut}&image={$_CFG['contacts']['url_no_slash']}{$article->main_image}"
			role="listitem"
			target="_blank"
			title="ВКонтакте" 
		>
			<span class="fa fa-vk"></span>
		</a>
		
		<a
			class="facebook"
			href="https://www.facebook.com/sharer.php?u={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}&t={$article->content_cut}"
			role="listitem"
			target="_blank"
			title="Facebook"
		>
			<span class="fa fa-facebook"></span>
		</a>
		
		<a
			class="twitter"
			href="https://twitter.com/share?url={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}&text={$article->content_cut}"
			role="listitem"
			target="_blank"
			title="Twitter"
		>
			<span class="fa fa-twitter"></span>
		</a>
		
		<a
			class="google"
			href="https://plusone.google.com/_/+1/confirm?hl=en&url={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}"
			role="listitem"
			target="_blank"
			title="Google+"
		>
			<span class="fa fa-google-plus"></span>
		</a>
		
		<a
			class="linkedin"
			href="https://www.linkedin.com/shareArticle?mini=true&url={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}"
			role="listitem"
			target="_blank"
			title="LinkedIn" 
		>
			<span class="fa fa-linkedin"></span>
		</a>
		
		<a
			class="pinterest"
			href="https://www.pinterest.com/pin/create/button/?url={$_CFG['contacts']['url']}{$_PAGE->name}/{$article->slug}&description={$article->content_cut}&media={$_CFG['contacts']['url_no_slash']}{$article->main_image}"
			role="listitem"
			target="_blank"
			title="Pinterest" 
		>
			<span class="fa fa-pinterest"></span>
		</a>

	</div>
