<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">

    <?php header('Content-type: text/html; charset=iso-8859-1'); ?>
    <title>x.are</title>
    <base href="<?= base_url(); ?>" />

    <meta http-equiv="content-language" content="pt-br, pt, pt-pt, en-us">
    <meta name="ROBOTS" content="index,follow,noodp,noydir">
    <meta name="rating" content="GENERAL">
    <meta name="author" content="x.are">
    <meta name="copyright" content="Copyright � 2004-<?= date("Y"); ?> x.are, Todos os direitos reservados.">
    <meta name="owner" content="deepr Dev Team">
    <?php if(isset($seo) && strlen($seo)) echo $seo; ?>

    <?php if(isset($feed)) { ?>
    <link rel="alternate" type="application/rss+xml" title="<?= $feed_title; ?>" href="<?= $feed ?>" />
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

<script type="text/javascript">
function cb(e, s) {
    e.src = (s) ? "images/b_ok-on.gif" : "images/b_ok-off.gif";
    e.style.cursor = (s) ? "pointer" : "";
}

function doSearch() {
    if(!$("#search").val()) {
        alert("D'uh!!!");
        return false;
    }
    document.location.href = 's/' + $("#search").val();
    return false;
}
</script>

<body>

<?php $this->load->view("common/login"); ?>

<div style="width: 100%;" align="center">

<img src="images/common/dot.gif" width="1" height="20" /><br />

<table width="80%">
<form action="search" method="get" onsubmit="return doSearch();">
<tr>
<td width="1%" nowrap="nowrap" rowspan="2"><img src="images/xare_logo.gif" /></td>
<td><input type="text" name="q" id="search" class="search" value="" /></td>
<td width="1%" nowrap="nowrap"><img src="images/common/dot.gif" width="8" height="1" /><img src="images/b_ok-off.gif" onmouseover="cb(this, 1);" onmouseout="cb(this, 0);" border="0" onclick="doSearch();" /></td>
</tr>
</form>

<tr><td><img src="images/common/dot.gif" width="1" height="15" /></td></tr>
</table>

<div style="background-color: #f1f1f1; padding: 2px; font-size: 14px; line-height: 28px; text-align: center; border-top: 1px solid #dddddd; width: 97%">
    <?php
        $cnt = 1;
        foreach($content->result() as $row) {
            echo "<a href='".$row->name_url."'>".$row->name."</a> (".$row->related.")";
            if($cnt != $content->num_rows()) echo " - ";
            $cnt++;
        }
    ?>
</div>

</div>