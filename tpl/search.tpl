{extends "classes/content.tpl"}

{block "content" append}

	<div class="clearfix margin-b-lg">
		{$_PAGE->content}
	</div>

	<form action="/search/" class="margin-b-xl" method="get">
		<div class="input-group search-result-input-wrap margin-b-lg">
			<span class="input-group-btn">
				<button class="btn btn-lg btn-primary" title="Поиск" type="submit">
					<span class="fa fa-search"></span>
				</button>
			</span>
			<input class="form-control input-lg" name="q" placeholder="Поиск" value="{$search_query->query_raw|default}">
		</div>
	</form>

	{if ($search_query->results === null)}
	
		<p class="alert alert-info">
			Введите строку для поиска.
		</p>

	{else}
	
		{if (sizeof($search_query->results) > 0)}
	
			<div class="search-result-details">
				<p>
					Найдено страниц: <strong>{sizeof($search_query->results)}</strong>
				</p>
			</div>
	
			{foreach $search_query->results as $item}
		
				<div class="search-result">
					<h3><a href="/{$item['href']}">{$item['title']}</a></h3>
					<p>
						{$item['content_cut']}
					</p>
					<p>
						<small>
							<a href="/{$item['href']}">{$_CFG['contacts']['url']}{$item['href']}</a>
						</small>
					</p>
				</div>
		
			{/foreach}
	
		{else}
	
			<p class="alert alert-info">
				По вашему запросу ничего не найдено. :-(
			</p>
	
		{/if}

	{/if}

{/block}