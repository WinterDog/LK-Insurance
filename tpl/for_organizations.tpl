{extends "classes/content_ins_o.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr" style="background-image: url(/css/img/front-header-bgr/organizations.jpg);">
			<div class="page-title color-alt">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active" href="">
						<h4>Корпоративным клиентам</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					{*<h1>Страховая защита во время отдыха.</h1>*}
				</div>
			</div>
		</div>
	</section>

{/block}

{block "content_h1"}
	
{/block}

{block "content" append}

	<p class="alert alert-danger margin-b-lg">
		Корпоративные разделы находятся в разработке. Приносим извинения за неудобства.
	</p>

	{$_PAGE->content}

{/block}