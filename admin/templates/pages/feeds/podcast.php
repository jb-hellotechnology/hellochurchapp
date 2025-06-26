<?php

require '../../../vendor/autoload.php';

header('Content-type: text/xml; charset=utf-8');

$church = church_by_slug(perch_get('churchSlug'));
$podcast = podcast_public($church['churchID']);
?>
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?= $podcast['podcastName'] ?></title>
    <link><?= $church['churchWebsite'] ?></link>
    <language>en-gb</language>
    <copyright>&#169; <?= date('Y') ?> <?= $church['churchName']?></copyright>
    <itunes:author><?= $church['churchName'] ?></itunes:author>
    <description>
	<?= $podcast['podcastDescription'] ?>
    </description>
    <itunes:image
      href="http://app.churchplanner.co.uk/feed/<?= perch_get('churchSlug') ?>/podcast.jpg"
    />
    <itunes:category text="Religion &amp; Spirituality">
      <itunes:category text="Christianity"/>
    </itunes:category>
    <itunes:explicit>false</itunes:explicit>
    <itunes:owner>
    	<itunes:name><?= $church['churchName'] ?></itunes:name>
		<itunes:email><?= $church['churchEmail'] ?></itunes:email>
    </itunes:owner>
<?php
	podcast_feed($church['churchID']);
?>
  </channel>
</rss>