UPDATE	`user`
SET		currentlogin= '{$currentlogin|@mysql_escape_string}',
		lastlogin	= '{$lastlogin|@mysql_escape_string}',
		currentip	= '{$currentip|@mysql_escape_string}',
		lastip		= '{$lastip|@mysql_escape_string}'
WHERE	id			= '{$id|@mysql_escape_string}'