<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">

    <?php header('Content-type: text/html; charset=iso-8859-1'); ?>
    <title>xare-a-link: <?= $page_title; ?></title>
    <base href="<?= base_url(); ?>" />

    <meta http-equiv="content-language" content="pt-br, pt, pt-pt, en-us">
    <meta name="ROBOTS" content="index,follow,noodp,noydir">
    <meta name="rating" content="GENERAL">
    <meta name="author" content="xare">
    <meta name="copyright" content="Copyright © 2004-<?= date("Y"); ?> x.are, Todos os direitos reservados.">
    <meta name="owner" content="xare Dev Team">
	<?php if(isset($seo) && strlen($seo)) echo $seo; ?>

    <?php if(isset($rss)) { ?>
    <link rel="alternate" type="application/rss+xml" title="<?= $feed_name; ?>" href="<?= $feed_url ?>" />
    <?php } ?>

    <link rel="Shortcut Icon" href="<?= base_url(); ?>favicon.ico" type="image/x-icon" />
    <link rel="StyleSheet" href="<?= base_url(); ?>css/xare_content.css" title="Default stylesheet!" type="text/css">
    <script type="text/javascript">var user_logged = <?= $this->session->userdata('logged') ? "true" : "false"; ?></script>
    <script src="<?= base_url(); ?>js/jquery.js" language="JavaScript"></script>
    <!--[if lt IE 7]><script src="<?= base_url(); ?>js/ie7/ie7-standard-p.js" type="text/javascript"></script><![endif]-->
    <noscript>
        <meta http-equiv="refresh" content="0;URL=<?= base_url(); ?>error/nojs">
    </noscript>
</head>

<body>

<div id="div_proc"><table border="0" cellpadding="0" cellspacing="0"><tr><td><img src="images/ajax_loader-topright.gif" border="0"></td><td style="color: #ffffff; font-size: 12px;">&nbsp;<b>Aguarde...</b></td></tr></table></div>
<div id="div_load">Atualizando...</div>

<?php $this->load->view("common/login"); ?>

<!-- Global table (close @ layout_bottom.php) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td align="center" id="first_td">
    
    <table width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr><td bgcolor="#ffffff" style="padding: 10px">

<?php $this->load->view("common/header"); ?>
