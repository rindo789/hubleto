<?php

set_time_limit(600);

ini_set('html_errors', "1");
ini_set('error_prepend_string', "<pre style='color: #333; font-face:monospace; font-size:8pt;'>");
ini_set('error_append_string', "</pre>");

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);

define('_ADIOS_ID', 'HubletoMain-{{ accountUid }}');

// load configs
require_once(__DIR__ . "/ConfigEnv.php");

// load autoloaders
require("{{ srcFolder }}/vendor/autoload.php");

// load entry class
require("{{ srcFolder }}/src/Main.php");

// render
$main = new HubletoMain($config);
echo $main->render();
