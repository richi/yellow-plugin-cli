<?php
// Source: https://github.com/richi/
// This file may be used and distributed under the terms of the public license.

// cli plugin
class YellowCli
{
	const VERSION = "0.1.3";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
	}
	
	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template")=="cli")
		{
			if(PHP_SAPI=="cli") $this->yellow->page->error(500, "Static website not supported!");
			
			$this->yellow->page->set("cliHelp", "Please Login!");
			$this->yellow->page->set("cliResults", "");
			
			if($this->yellow->plugins->isExisting("commandline") &&
			   $this->yellow->plugins->isExisting("webinterface") &&
			   $this->yellow->plugins->get("webinterface")->isUser())
			{
				$help = $this->yellow->plugins->get("commandline")->getCommandHelp();
				$this->yellow->page->set("cliHelp", implode("\n", $help));

				$query = trim($_REQUEST["query"]);
				if(!empty($query))
				{
				 	$_SERVER["LOCATION_ARGS"] = "";
					
					ob_start();
					$args = $this->yellow->toolbox->getTextArgs($query);
					$this->yellow->command($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
					$result = ob_get_contents();
					ob_end_clean();
					
					$this->yellow->page->setHeader("Cache-Control", "max-age=0, no-cache");

					if($args[0]=="build")
					{
						ob_start();
						$location = $this->yellow->page->getLocation(true);
						echo "<html><h1>$args[1]:</h1><pre>\n$result</pre><a href=$location>Back</a></html>";
						$this->yellow->page->setOutput(ob_get_contents());
						ob_end_clean();
					} else {
						$this->yellow->page->set("cliResults", $result);
					}
				}
			}
		}
	}
}

$yellow->plugins->register("cli", "YellowCli", YellowCli::VERSION);
?>
