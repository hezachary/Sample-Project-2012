INSERT
INTO	pie_recycler
		(
		recycled_time,
		table_name,
		recycled_data,
		short_desc
		)
VALUES
{foreach name=data_content from=$ary_data item=data}
		(
		'{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}',
		'{$table}',
		'{$data|@serialize|@mysql_escape_string}',
		'{$data|@print_r:true|@mysql_escape_string}'
		){if !$smarty.foreach.data_content.last},{/if}
{/foreach}