<?php
$tools = json_decode(file_get_contents("tools.json"), true);
if ($_SERVER["REQUEST_URI"] == "/")
{
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<title>omnitool.app</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	</head>
	<body>
		<script src="theme.js"></script>
		<div class="container">
			<h1 class="mt-3">omnitool.app</h1>
			<p>The following tools are currently available:</p>
			<ul>
				<?php foreach ($tools as $tool): ?>
					<li><a href="<?=$tool["slug"];?>"><?=$tool["name"];?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</body>
	</html>
	<?php
}
else
{
	function getToolBySlug($slug)
	{
		global $tools;

		foreach ($tools as $tool)
		{
			if ($tool["slug"] == $slug)
			{
				return $tool;
			}
		}
		return null;
	}

	$slug = substr($_SERVER["REQUEST_URI"], 1);
	$slug = strstr($slug, "#", true) ?: $slug;
	$tool = getToolBySlug(strtolower($slug));
	if (!$tool)
	{
		http_response_code(404);
		exit;
	}

	// Ensure canonical casing
	if ($slug != $tool["slug"])
	{
		header("Location: /".$tool["slug"]);
		exit;
	}
	http_response_code(200);
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<title><?=$tool["name"];?> | omnitool.app</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	</head>
	<body>
		<script src="theme.js"></script>
		<div class="container">
			<h1 class="mt-3"><?=$tool["name"];?></h1>
			<?php
			if (array_key_exists("description", $tool))
			{
				?>
				<p><?=$tool["description"];?></p>
				<?php
			}
			?>

			<script type="pluto">tool = {}</script>

			<label for="input" class="form-label">Input</label>
			<textarea id="input" class="form-control" style="height:calc(50vh - 160px)" autofocus></textarea>

			<?php if (array_key_exists("numeric", $tool)): ?>
				<label for="format" class="form-label mt-3">Output Format</label>
				<select id="format" class="form-control">
					<option>Decimal (Signed)</option>
					<option>Decimal (Unsigned)</option>
					<option>Hexadecimal (Unsigned)</option>
				</select>

				<script type="pluto">tool.numeric = true</script>
			<?php endif; ?>

			<label for="output" class="form-label mt-3">Output</label>
			<textarea id="output" class="form-control" style="height:calc(50vh - 160px)" readonly></textarea>

			<?php if (array_key_exists("related", $tool)): ?>
				<h2 class="mt-3">Related Tools</h2>
				<ul>
					<?php foreach ($tool["related"] as $rslug): ?>
						<li><a href="/<?=$rslug;?>"><?=getToolBySlug($rslug)["name"];?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<script src="https://pluto-lang.org/wasm-builds/out/libpluto/0.9.5/libpluto.js"></script>
		<script src="https://pluto-lang.org/PlutoScript/plutoscript.js"></script>
		<script src="platform.js"></script>
		<script type="pluto" src="platform.pluto"></script>
		<script type="pluto" src="tools/<?=$tool["slug"];?>.pluto"></script>
	</body>
	</html>
	<?php
}
?>
