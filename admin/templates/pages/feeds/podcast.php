<?php

require '../../../vendor/autoload.php';

header('Content-type: text/xml; charset=utf-8');

$church = hello_church_church_public(perch_get('churchID'));
?>
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?= $church['churchName'] ?></title>
    <link><?= $church['churchWebsite'] ?></link>
    <language>en-gb</language>
    <copyright>&#169; <?= date('Y') ?> <?= $church['churchName']?></copyright>
    <itunes:author><?= $church['churchName'] ?></itunes:author>
    <description>
	Just testing.
    </description>
    <itunes:image
      href="https://hellochurch.tech/wp-content/uploads/2025/01/rafaela-biazi-4pJ9gO6NTAw-unsplash-2048x1356.jpg"
    />
    <itunes:category text="Religion &amp; Spirituality">
      <itunes:category text="Christianity"/>
    </itunes:category>
    <itunes:explicit>false</itunes:explicit>
    <itunes:email>jack@hellotechnology.co.uk</itunes:email>
<?php
	podcast_feed(perch_get('churchID'));
?>
  </channel>
</rss>