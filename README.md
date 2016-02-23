CLI plugin
==========

This is a HTML - CLI gateway.

Use the yellow command line interface without shell access.


How to install?
---------------
1. Download and install [Yellow](https://github.com/datenstrom/yellow/).  
2. Download [cli.php](cli.php?raw=true), copy it into your `system/plugins` folder.  
3. Download [cli.html](cli.html?raw=true), copy it into your `system/themes/templates` folder.  
4. Download [content-cli.php](content-wiki.php?raw=true), copy it into your `system/themes/snippets` folder.  
5. Create a new folder '9-cli' in your `content` folder.
6. Add [page.txt](page.txt?raw=true) to your `/content/9-cli` folder.

To uninstall delete the plugin files.

.htaccess
---------

Optionally you can use this .htaccess file to serve the data directly out of the cache.

    <IfModule mod_rewrite.c>

    RewriteEngine on

    DefaultType text/html
    DirectoryIndex index.html yellow.php

    RewriteCond %{DOCUMENT_ROOT}/cache%{REQUEST_URI}index.html -f
    RewriteRule ^ /cache%{REQUEST_URI}index.html [L] 

    RewriteCond %{DOCUMENT_ROOT}/cache%{REQUEST_URI} -f
    RewriteRule ^ /cache%{REQUEST_URI} [L] 

    RewriteRule ^(content|system)/ error [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ yellow.php [L]

    </IfModule>

On some shared servers (STRATO for example) DOCUMENT_ROOT is not working. If this is the case simply replace `%{DOCUMENT_ROOT}` with the real path.

