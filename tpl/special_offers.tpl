{extends "classes/content.tpl"}

{block "content" append}

	<div class="content-news">

		<section>

			{foreach $articles as $article}

				<article>
					<div class="row">
						<div class="col-sm-3">
							<img alt="{$article->title}" class="img-responsive margin-b" src="{$article->main_image_thumb}">
						</div>
						<div class="col-sm-9">
							<header>
								<h3>
									{$article->create_date_s}
								</h3>
								<h4>
									<a href="/special_offers_view/{$article->slug}">
										{$article->title}
									</a>
								</h4>
							</header>

							<p>
								{$article->content_cut}
							</p>

							{if ($_PAGES['article_edit']->rights > 0)}
								<div class="margin-tb">
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
						</div>
					</div>
				</article>

			{foreachelse}

				<p class="alert alert-info">
					Пока акций нет, но скоро обязательно появятся!
				</p>

			{/foreach}

		</section>

	</div>

	{if ($_PAGES['article_edit']->rights > 0)}
		{include "inc/modal_ref_dialog.tpl" page_name='article_edit'}
	{/if}

{/block}