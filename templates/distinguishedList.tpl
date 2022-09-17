{capture assign='pageTitle'}{$__wcf->getActivePage()->getTitle()}{/capture}

{capture assign='headContent'}
	<link rel="canonical" href="{link controller='DistinguishedList' object=$controllerObject}{/link}">
{/capture}

{include file='header'}

{if !$sortFields|empty}
	<div class="section tabMenuContainer staticTabMenuContainer">
		<nav class="tabMenu">
			<ul>
				{foreach from=$sortFields item=field}
					<li{if $sortField == $field} class="active"{/if}><a href="{link controller='DistinguishedList' object=$controllerObject}sortField={$field}&sortOrder=DESC{/link}">{lang}wcf.user.distinguished.tab.{$field}{/lang}</a></li>
				{/foreach}
			</ul>
		</nav>
		{if $objects|count}
			<div class="tabMenuContent">
				<ol class="containerList userList">
					{assign var='counter' value=0}
					{foreach from=$objects item=user}
						{if $user->$sortField}
							{include file='distinguishedListItem'}
							{assign var='counter' value=$counter + 1}
						{/if}
					{/foreach}
				</ol>
				{if !$counter}
					<p class="info">{lang}wcf.global.noItems{/lang}</p>
				{/if}
			</div>
		{else}
			<p class="info">{lang}wcf.global.noItems{/lang}</p>
		{/if}
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
	{hascontent}
		<nav class="contentFooterNavigation">
			<ul>
				{content}
					
					{event name='contentFooterNavigation'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</footer>

{include file='footer'}
