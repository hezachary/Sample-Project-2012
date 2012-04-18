INSERT
INTO	login_log
SET		date		= '{$query->date}',
		attempt		= 1,
		successed	= '{if $query->flag=='successed'}1{else}0{/if}',
		failed		= '{if $query->flag=='failed'}1{else}0{/if}'