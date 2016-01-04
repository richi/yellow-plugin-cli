<div class="content main">
<h1><?php echo $yellow->page->getHtml("title") ?></h1>
<pre><code><?php echo $yellow->page->getHtml("cliHelp") ?></code></pre>
<form class="cli-form" action="<?php echo $yellow->page->getLocation() ?>" method="post">
<input class="form-control" type="text" name="query" value="<?php echo htmlspecialchars($_REQUEST["query"]) ?>" />
<input class="btn cli-btn" type="submit" value="Do" />
<input type="hidden" name="clean-url" />
</form>
<pre><code><?php echo $yellow->page->getHtml("cliResults") ?></code></pre>
</div>
