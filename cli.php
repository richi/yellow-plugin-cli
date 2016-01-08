<?php
// This file may be used and distributed under the terms of the WTF public license.

// cli plugin
class YellowCli
{
	const Version = "0.1.2";
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
			
			$location = $this->yellow->page->getLocation();
			$interface = $this->yellow->plugins->get('webinterface');

			if ($interface->isUser())
			{
				$cmd = $this->yellow->plugins->get('commandline'); 
				$helpTxt = implode("\n", $cmd->getCommandHelp());
				
				$this->yellow->page->set("cliHelp", $helpTxt);

				$query = trim($_REQUEST["query"]);
				$tokens = array_filter(explode(' ', $query), "strlen");
				if(!empty($tokens))
				{
					array_unshift($tokens, "dummy");
					ob_start();
					$cmd->onCommand($tokens);
					$result = ob_get_contents();
					ob_end_clean();
					
					$this->yellow->page->setHeader("Cache-Control", "max-age=0, no-cache");

					if ($tokens[1] == "build")
					{
						ob_start();
						echo "<html><h1>$tokens[1]:</h1><pre>\n$result</pre><a href=$location>Back</a></html>";
						$this->yellow->page->setOutput(ob_get_contents());
						ob_end_clean();
					}
					else
					{
						$this->yellow->page->set("cliResults", $result);
					}
				}
			}
		}
	}
}

$yellow->plugins->register("cli", "YellowCli", YellowCli::Version);
?>
