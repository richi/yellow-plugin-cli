<?php
// This file may be used and distributed under the terms of the public license.

// cli plugin
class YellowCli
{
	const Version = "0.1.1";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
	}
	
	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template") == "cli")
		{
			if(PHP_SAPI == "cli") $this->yellow->page->error(500, "Static website not supported!");
			
			$this->yellow->page->set("cliHelp", "Please Login!");
			$this->yellow->page->set("cliResults", "");
			
			$interface = $this->yellow->plugins->get('webinterface');
			if ($interface->isUser())
			{
				$cmd = $this->yellow->plugins->get('commandline'); 
				$this->yellow->page->set("cliHelp", implode("\n", $cmd->getCommandHelp()));

				$query = trim($_REQUEST["query"]);
				$tokens = array_filter(explode(' ', $query), "strlen");
				if(!empty($tokens))
				{
					array_unshift($tokens, "dummy");
					ob_start();
					$cmd->onCommand($tokens);
					$this->yellow->page->set("cliResults", ob_get_contents());
					ob_end_clean();

					$this->yellow->page->setHeader("Cache-Control", "max-age=0, no-cache");
				}
			}
		}
	}
}

$yellow->plugins->register("cli", "YellowCli", YellowCli::Version);
?>
