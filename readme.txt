=== RSS Reply via email ===
Contributors: jeherve
Tags: widget, on this day
Stable tag: 1.0.1
Requires at least: 6.8
Requires PHP: 8.1
Tested up to: 6.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a reply-to email address for each post in your RSS feeds.

== Description ==

Some RSS readers display an email button under each post, if the RSS feed includes a reply-to email address. This plugins adds that email address to all posts in all your RSS feeds.

This plugin was inspired by [this article](https://florianziegler.com/journal/add-your-email-address-to-your-rss-feed) by Florian Ziegler.

== Installation ==

* Go to Plugins > Add New, search, and install.
* The plugin will work out of the box. All your RSS feeds will include a reply-to email address.

== FAQ ==

= How do I pick the email address that will be used in RSS feeds? =

The plugin uses each author's email address, as defined for each account under the Users menu.

= How do I customize the email address that will be used in RSS feeds? =

If you do not want to use the account's email address, you can set a custom email address thanks to the `jeherve_rss_reply_via_email_address` filter:

```php
add_filter( 'rss_reply_via_email_author_info', function( $author_info, $author_id, $post ) {
	$author_info['email'] = 'your@email.com';
	return $author_info;
}, 10, 3 );
```

== Screenshots ==

1. RSS Reader example

== Changelog ==

### [1.0.1] - 2025-06-25
#### Fixed
* Remove duplicated hook
* Remove text prepended to email address

### [1.0.0] - 2025-06-17
#### Added
* Initial public release.

