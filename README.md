# 561-remediation
A plugin for resolving the Wordpress 5.6.1 issue that interferes with updating custom post types (and potentially page, post, etc.)

## How to Use
Install 561.php in your plugins folder in Wordpress.

## Why?
Because I discovered that we had a bunch of sites that were intentionally running on 5.6.1 and couldn't submit updates to custom taxonomies, so I had to fix the problem in a way that didn't involve touching the functions.php on each site.  Thus, a plugin.  

## References 
https://core.trac.wordpress.org/ticket/52440 
