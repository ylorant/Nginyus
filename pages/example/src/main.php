<?php

class Example
{
	private $site;
	private $main;
	
	public function __construct($site, $main)
	{
		$this->site = $site;
		$this->main = $main;
		
		$site->addPage('/', $this, 'index');
		$site->addPage('/secret', $this, 'secret');
		$site->addFilePage('/js/(.+)', 'static/js/$1');
		$site->addFilePage('/css/(.+)', 'static/css/$1');
		$site->addFilePage('/images/(.+)', 'static/images/$1');
		$site->addErrorPage(404, $this, 'error404');
	}
	
	public function index($id, $data)
	{
		$view = new NginyUS_View("views/index.php");
		
		$view->test = "hello, world !";
		
		$this->main->BufferAppendData($id, $view->render());
		$this->main->sendBuffer($id);
	}
	
	public function secret($id, $data)
	{
		if(!$this->main->authenticate($id, $data, array($this, 'userCheck')))
			return $this->error403($id, $data);
		
		$view = new NginyUS_View("views/index.php");
		
		$view->test = "Authentified !";
		
		$this->main->BufferAppendData($id, $view->render());
		$this->main->sendBuffer($id);
	}
	
	public function error404($id, $data)
	{
		$this->main->BufferSetReplyCode($id, 404);
		$this->main->BufferAppendData($id, 'Puisque c\'est comme ça, je me lance dans le café soluble "qualité filtre" !<div style="text-align:right">&mdash;Maxwell</div>');
		$this->main->sendBuffer($id);
	}
	
	public function error403($id, $data)
	{
		$this->main->BufferSetReplyCode($id, 403);
		$this->main->BufferAppendData($id, '<h1>Tu te crois où toi ?</h1>');
		$this->main->sendBuffer($id);
	}
	
	public function userCheck($user, $passwd)
	{
		//Parsing password file
		$userFile = parse_ini_file('auth');
		
		//~ chdir($cwd); //Returning to the webadmin root.
		
		if(!isset($userFile[$user]) || $userFile[$user] != $passwd)
			return FALSE;
		
		return TRUE;
	}
}

$this->addClasses(array('Example'));
