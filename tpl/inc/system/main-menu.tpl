	<nav class="main-menu" id="main-menu">
		<div class="main-menu-wrap">
			<div class="main-menu-content">
				<a class="close" href="javascript:;" id="main-menu-close-btn">
					<span class="fa fa-times"></span>
				</a>
				<a class="logo" href="/">
					<img alt="Личный кабинет страхователя" class="img-responsive" src="/css/img/logo-1.png" title="Главная страница">
				</a>

				<ul class="list-unstyled menu-list-0" id="main-menu">

					{function "print_menu_items" level=0}
						{foreach from=$items item=item}
							{if ($item->id == 3)}
								{continue}
							{/if}
							<li
								class="section {if ($item->page_id == $_PAGE->id)}active{/if}"
							>
								<a
									{if (sizeof($item->children) > 0)}
										{if (!$item->is_active)}
											aria-controls="mm-section-{$item->id}"
											aria-expanded="{if ($item->open)}true{else}false{/if}"
											class="collapse-trigger {if (!$item->open)}collapsed{/if}"
											data-target="#mm-section-{$item->id}"
											data-toggle="collapse"
										{/if}
									{/if}

									{if ($item->is_active)}
										href="/{$item->name}/{if ($item->name == 'pages_edit')}?id={$_PAGE->id}{/if}"
									{else}
										href="javascript:;"
									{/if}
								>
									{$item->title}
									{if ($item->page_id == $_PAGE->id)}
										<span class="sr-only">(текущая)</span>
									{/if}
								</a>

								{if (sizeof($item->children) > 0)}
									<ul
										class="{if (!$item->is_active)}collapse{/if} {if ($item->open)}in{/if} list-unstyled menu-list-{$level+1}"
										id="mm-section-{$item->id}"
										sf-id="main-menu-collapse"
									>
										{print_menu_items items=$item->children level=$level+1}
									</ul>
								{/if}
							</li>
						{/foreach}
					{/function}

					{print_menu_items items=$top_menu->items level=0}

					{if ($_PAGES['admin_menu']->rights > 0)}

						{print_menu_items items=$admin_menu->items level=0}

					{/if}

				</ul>

				<script>
					$(function ()
					{
						// Hide all other collapsible lists when we open any.
						$('#main-menu [sf-id="main-menu-collapse"]').on('show.bs.collapse', function ()
						{
							var $this = $(this),
								$collapseItems = $('#main-menu [sf-id="main-menu-collapse"]'),
								$parents = $this.parents('[sf-id="main-menu-collapse"]'),
								$children = $this.find('[sf-id="main-menu-collapse"]');

							$collapseItems.not($this).not($parents).not($children).collapse('hide');
						});
					});
				</script>

			</div>

			<div class="site-search-form-wrap">
				<form id="site-search-form" action="/search/" method="get">
					<div class="site-search-wrap">
						<span class="fa fa-search"></span>
						<input class="form-control input-lg" name="q" placeholder="Поиск" type="text" value="">
						<button hidden type="submit">Поиск</button>
					</div>
				</form>
			</div>
		</div>
	</nav>