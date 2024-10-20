<?php
$tools = json_decode(file_get_contents("tools/__manifest.json"), true);

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

function getToolsByCategory()
{
	global $tools;

	$tools_by_category = [];
	foreach ($tools as $tool)
	{
		$tools_by_category[$tool["category"]][] = $tool;
	}
	return $tools_by_category;
}

if ($_SERVER["REQUEST_URI"] == "/")
{
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<title>data.recipes</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	</head>
	<body>
		<script src="theme.js"></script>
		<div class="container">
			<h1 class="mt-3">data.recipes</h1>
			<p>The following tools are currently available:</p>
			<ul>
				<?php foreach (getToolsByCategory() as $category => $tools_in_category): ?>
					<li>
						<?=$category;?>
						<ul>
							<?php foreach ($tools_in_category as $tool): ?>
								<li><a href="<?=$tool["slug"];?>"><?=$tool["name"];?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</body>
	</html>
	<?php
}
else
{
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
		<title><?=$tool["name"];?> | data.recipes</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	</head>
	<body>
		<script src="theme.js"></script>
		<nav class="navbar navbar-expand-lg bg-body-tertiary">
			<div class="container">
				<a class="navbar-brand" href="/">data.recipes</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<?php foreach (getToolsByCategory() as $category => $tools_in_category): ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle<?php if ($category == $tool["category"]) { echo " active"; } ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?=$category;?></a>
								<ul class="dropdown-menu">
									<?php foreach ($tools_in_category as $nav_tool): ?>
										<li><a class="dropdown-item<?php if ($nav_tool["slug"] == $tool["slug"]) { echo " active"; } ?>" href="/<?=$nav_tool["slug"];?>"><?=$nav_tool["name"];?></a></li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</nav>
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
			<textarea id="input" class="form-control" style="height:calc(50vh - 180px)" autofocus></textarea>

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
			<?php if (array_key_exists("visual", $tool)): ?>
				<img id="output" style="display:block" />
				<script type="pluto">tool.visual = true</script>
			<?php else: ?>
				<textarea id="output" class="form-control" style="height:calc(50vh - 180px)<?php if (array_key_exists("monospace", $tool)): ?>;font-family:monospace<?php endif; ?>" readonly></textarea>
			<?php endif; ?>

			<?php if (array_key_exists("related", $tool)): ?>
				<h2 class="mt-3">Related Tools</h2>
				<ul>
					<?php foreach ($tool["related"] as $rslug): ?>
						<li><a href="/<?=$rslug;?>"><?=getToolBySlug($rslug)["name"];?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<script src="vendor/libpluto.js"></script>
		<script src="https://pluto-lang.org/PlutoScript/plutoscript.js"></script>
		<script src="platform.js"></script>
		<script type="pluto" src="platform.pluto"></script>
		<script type="pluto" src="tools/<?=$tool["slug"];?>.pluto"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	</body>
	</html>
	<?php
}
