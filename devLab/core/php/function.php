<?php
	/*
	 * Formatted var_dump
	 */
	echo "<pre>";
	ini_set("xdebug.var_display_max_children", -1);
	ini_set("xdebug.var_display_max_data", -1);
	ini_set("xdebug.var_display_max_depth", -1);

	/*
	 * Easier call of var_dump
	 */
	function dump($expression)
	{
		return var_dump($expression);
	}
