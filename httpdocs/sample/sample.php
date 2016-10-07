<html>
	<head>
		<title>Sample configuration page</title>
	</head>
	<body>	

	<p>This page shows off a sample auto-generated config in use by PHP. It uses <strong>TEST_VAR_NUMERIC</strong> and
		<strong>TEST_VAR_STRING</strong> defined in <strong>/config_test/cfg__test.php</strong> for its values, which needs to exist.</p>

	<?php
	
	require_once("../config_test/cfg__test.php");
	echo "<p>Numeric var: " . TEST_VAR_NUMERIC . "</p>";
	echo "<p>String var: " . TEST_VAR_STRING . "</p>";
		
	?>
	
	</body>
</html>