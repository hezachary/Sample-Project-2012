SELECT	id,
		username,
		password,
		firstname,
		lastname,
		email,
		icq,
		skype,
		yahoo,
		aim,
		msn,
		phone1,
		phone2,
		department,
		address,
		city,
		country,
		lang,
		timezone,
		firstaccess,
		currentlogin,
		lastlogin,
		currentip,
		lastip,
		secret,
		picture,
		url,
		description,
		timemodified,
		role
FROM	`user`
WHERE	username = '{$username|@mysql_escape_string}'
AND		password = '{$password|@mysql_escape_string}'
LIMIT	1