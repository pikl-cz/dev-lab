<script>
	/*
	 * Timer for loading to refresh
	 */
	
	/*
	 * Run php to get data
	 */
	var file = 'getData.php';
	var promise = $.getJSON("http://devlab.localhost/_experiments/okhouse/getData.php");
	alert(promise);
	
	/*
	 * Draw it nice
	 */	
	function showname(username)
	{
		alert(username);
	}
	showname('test');
</script>