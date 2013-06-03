<?php

error_reporting(E_ALL);
define('E_DEBUG', 32768);

include('core/framework.class.php');
include('core/main.class.php');
include('core/events.class.php');
include('core/systemPages.class.php');
include('core/siteManager.class.php');
include('core/view.class.php');

//Signal handling management config
pcntl_signal(SIGTERM, "sig_handler");

//Short function handling the sole signal needed, but extendable
function sig_handler($signo) 
{
	switch ($signo)
	{
		case SIGTERM:
			//Program stop handling
			Main::$continue = FALSE;
			exit;
			break;
	}

}

$argv = NginyUS::parseArgs($argv);

if(isset($argv['v']))
	define('VERBOSE', TRUE);
else
	define('VERBOSE', FALSE);

$main = new NginyUS();
$main->loadConfig();

$main->connect();
$main->run();
