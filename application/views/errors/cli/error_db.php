<?php defined('BASEPATH') OR exit('No direct script access allowed');

$color = new Colors\Color;

echo $color('Database error: ')->white->bold->bg_red;
echo $color($heading)->white->bg_red . PHP_EOL;
echo $color(str_replace("\t", '', $message))->red . PHP_EOL;