<?php echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"; ?>
<rss version="2.0">
<channel>

    <title><![CDATA[<?= $feed_name ?>]]></title>
    <link><?= base_url() ?><?=$feed_url?></link>

    <description>Links selecionados!</description>
    <language>pt-br</language>
    <copyright>Copyright xare.us - 2007/<?=gmdate("y", time())?></copyright>
    <lastBuildDate><?= date("r", time()); ?></lastBuildDate>
    <pubDate><?= date("r"); ?></pubDate>

    <?php foreach($feed->result() as $row): ?>
    <item>
        <title><![CDATA[<?= $row->name ?><?= (strlen($row->link) && $row->link != "0" && $row->link != "http://") ? "" : " >"; ?>]]></title>
        <description><![CDATA[
                <?php
                echo $row->short_desc;
                ?>
        ]]></description>
        <link><?= (strlen($row->link) && $row->link != "0" && $row->link != "http://") ? $row->link : base_url()."parse/".$row->id; ?></link>
        <guid isPermaLink="true">s</guid>
        <pubDate><?= date("r", $row->date_added); ?></pubDate>
    </item>
    <?php endforeach; ?>

    </channel>
</rss>