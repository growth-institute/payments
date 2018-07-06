<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $site->getPageTitle(); ?></title>
	<?php $site->metaTags(); ?>
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php $site->img('branding/favicon.ico'); ?>">
	<link rel="icon" href="<?php $site->img('branding/favicon-md.png'); ?>" type="image/png">
	<!-- Device-specific icons -->
	<link rel="apple-touch-icon" href="<?php $site->img('branding/favicon-sm.png'); ?>" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php $site->img('branding/favicon-md.png'); ?>" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php $site->img('branding/favicon-lg.png'); ?>" />
	<!-- Stylesheets -->
	<?php $site->includeStyles(); ?>
</head>
<body class="<?php $site->bodyClass() ?>">