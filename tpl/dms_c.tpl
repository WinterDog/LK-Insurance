{extends "classes/content.tpl"}

{block "header_block_text"}
	<p>
		Отправьте нам заявку на расчёт ДМС прямо сейчас!
		В течение 24 часов мы подготовим несколько самых подходящих вариантов по ряду компаний.
		Вам останется лишь выбрать — остальным займёмся мы.
	</p>
	<a class="btn btn-block btn-lg btn-warning" href="/dms_query_c/" role="button">
		Заявка на расчёт ДМС
	</a>
{/block}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr" style="background-image: url(/css/img/front-header-bgr/travel.jpg);">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active" href="">
						<h4></h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Отправьте нам заявку на расчёт ДМС прямо сейчас!</h1>
					<h3>
						В течение 24 часов мы подготовим несколько самых подходящих вариантов по ряду компаний.
						<br>
						Вам останется лишь выбрать — остальным займёмся мы.
					</h3>
				</div>
			</div>
		</div>
	</section>

{/block}

{block "content_h1"}
	
{/block}

{block "content" append}

	<a class="btn btn-lg btn-warning margin-b-lg" href="/dms_query_c/" role="button">
		Заявка на расчёт ДМС
	</a>

	<div class="clearfix">
		{$_PAGE->content}
	</div>

	<a class="btn btn-lg btn-warning margin-t-lg" href="/dms_query_c/" role="button">
		Заявка на расчёт ДМС
	</a>

{/block}