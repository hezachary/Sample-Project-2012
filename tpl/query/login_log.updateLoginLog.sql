UPDATE	login_log
SET		attempt		= attempt + 1,
{if $query->flag=='successed'}
		successed	= successed + 1
{else}
		failed		= failed + 1
{/if}
WHERE	date		= '{$query->date}'