<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= isset($title) ? $title : 'Page'; ?> - <?= Config::get('app.name'); ?></title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Local customizations -->
	<link rel="stylesheet" type="text/css" href="<?= resource_url('css/style.css'); ?>">
</head>
<body>

<div class="container">
	<div class="row">
		<a style="outline: none;" href="<?= site_url(); ?>"><img src="<?= resource_url('images/nova.png') ?>" alt="<?= Config::get('app.name') ?>"></a>
		<h1><?= $title ?></h1>
		<hr style="margin-top: 0;">
	</div>
	<?= $content; ?>
</div>

<footer class="footer">
	<div class="container-fluid">
		<div class="row" style="margin: 15px 0 0;">
			<div class="col-lg-12 text-right">
				Copyright &copy; <?= date('Y') ?> <a href="http://www.novaframework.com/" target="_blank"><strong>Nova Framework <?= VERSION; ?> / Kernel <?= App::version(); ?></strong></a> - All rights reserved.
			</div>
		</div>
	</div>
</footer>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- DO NOT DELETE! - Profiler -->

</body>
</html>