{extends "classes/content_ins.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr" style="background-image: url(/css/img/front-header-bgr/accident.jpg);">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active" href="">
						<h4>Страхование жизни</h4>
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

	{$_PAGE->content}

{/block}