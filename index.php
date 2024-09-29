<?php
$tools = json_decode(file_get_contents("tools.json"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>omnitool.app</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<div class="container">
	<script src="theme.js"></script>
	<h1 class="mt-2">omnitool.app</h1>
	<p>The following tools are currently available:</p>
	<ul>
		<?php foreach ($tools as $tool): ?>
			<li><a href="<?=$tool["slug"];?>"><?=$tool["name"];?></li>
		<?php endforeach; ?>
	</ul>
</div>
</html>
