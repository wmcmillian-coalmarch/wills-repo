<?php

$date = new DateTime('20160202T182453Z');
$date->setTimezone(new DateTimeZone('UTC'));

print $date->format('Y-m-d H:i:s e');