<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<link rel="shortcut icon" href="/template/images/logo.png" type="image/png">

	<?php
		foreach (scandir(ROOT."/template/css/")
			as $file ):?>
		<?php if (file_exists(
			ROOT."/template/css/".$file)
		&& endsWith($file, ".css")): ?>
		<link rel="stylesheet"
		type="text/css"
		href="/template/css/<?php echo $file; ?>">
		<?php endif; ?>
	<?php endforeach; ?>
	
</head>
<body>

	<link href="https://fonts.googleapis.com/css2?family=Vampiro+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">

	<header class="flex jcsa">
		<div class="header flex jcsb">
			<div id="header-logo">
				<a id="name-logo" href="/main">Voice</a>
			</div>

			<div id="header-menu">
				<ol id="header-menu-inside">
					<li class="header-menu"><a href="/main">Головна</a></li>
					<li class="header-menu"><a href="/about">Про нас</a></li>
				</ol>
			</div>

				<?php if(isset($_SESSION['id'])) { ?>
					<div id="header-account header-account-set">
						<a class="header-account-set-inner flex jcsa" href="/cabinet">
							<div class="header-account-img">
								<img src="/template/images/cabinet_icon.svg" alt="">
							</div>
							<div class="header-account-text flex fdc jcsa">
								<p>Кабiнет</p>
							</div>
						</a>
					</div>
				<?php } else {?>
					<div id="header-account">
						<a href="/login">
							<div id="header-account-inside">
								<p>Вхiд</p>
							</div>
						</a>
					</div>
				<?php } ?>
		</div>
	</header>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>