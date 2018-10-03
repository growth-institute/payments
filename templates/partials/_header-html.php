<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $site->getPageTitle(); ?></title>
	<?php $site->metaTags(); ?>
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php $site->img('branding/favicon.ico'); ?>?v=2">
	<link rel="icon" href="<?php $site->img('branding/favicon-md.png'); ?>?v=2" type="image/png">
	<!-- Device-specific icons -->
	<link rel="apple-touch-icon" href="<?php $site->img('branding/favicon-sm.png'); ?>?v=2">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php $site->img('branding/favicon-md.png'); ?>?v=2">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php $site->img('branding/favicon-lg.png'); ?>?v=2">
	<!-- Stylesheets -->
	<?php $site->includeStyles(); ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KTS6H8');</script>
	<!-- End Google Tag Manager -->
</head>
<body class="<?php $site->bodyClass() ?>">