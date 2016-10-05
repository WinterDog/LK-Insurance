{extends "classes/content.tpl"}

{block "header_bgr_class"}bgr-news{/block}
{block "header_class"}{/block}

{block "header_block"}{/block}

{block "content" append}

	<div class="content-news">
		<div class="row">

			<div class="col-sm-4 col-md-3 hidden-xs news-calendar">
				<section>
					<div class="cal-cur-date-wrap">
						<p class="cal-cur-date-today">
							сегодня
						</p>
						<p class="cal-cur-date-day">
							{$calendar_cur['day']}
						</p>
						<p class="cal-cur-date-month-year">
							{$calendar_cur['month_s']} {$calendar_cur['year']} г.
						</p>
					</div>

					<p class="text-muted">Выберите дату, чтобы увидеть новости за определённый день.</p>
					{if ($input['date'])}
						<p><a href="/news/">Показать все новости</a></p>
					{/if}

					<div id="news-calendar">
					</div>
				</section>
			</div>

			<script>
				$(function ()
				{
					var $calendar = $('#news-calendar');

					SetDatePickerDiv(
						$calendar,
						{
							maxDate:		g_today,
			            });

					{if ($input['date'])}
						$calendar.data('DateTimePicker').date('{$input['date']}');
					{/if}
					$calendar.on('dp.change', function (e)
					{
						// Trigger onchange event for input when the date in datepicker is changed.
						OpenUrl('/news/?date=' + e.date.format('DD.MM.YYYY'));
					});
				});
			</script>

			<div class="col-sm-8 col-md-9">
				<section>
					{*
					<div class="sectionheader">
						<a href="/news/">Архив новостей</a>
					</div>

					<div class="sectionheader">
						<a href="/news/">Другие новости...</a>
					</div>
					*}

					{foreach $articles as $article}

						<article>
							<header>
								<h3>
									{$article->create_date_s}
								</h3>
								<h4>
									<a href="/news_view/{$article->slug}">
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
						</article>

					{foreachelse}

						<p class="alert alert-info">
							Ничего не найдено.
						</p>

					{/foreach}

				</section>
			</div>

		</div>
	</div>

	{if ($_PAGES['article_edit']->rights > 0)}
		{include "inc/modal_ref_dialog.tpl" page_name='article_edit'}
	{/if}

{/block}