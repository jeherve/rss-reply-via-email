<?php
/**
 * Plugin Name: RSS Reply via email
 * Plugin URI: https://herve.bzh/
 * Description: Add a reply-to email address for each post in your RSS feeds.
 * Author: Jeremy Herve
 * Version: 1.0.0
 * License: GPL2+
 * Requires at least: 6.8
 * Requires PHP: 8.1
 * Text Domain: rss-reply-via-email
 *
 * @package Jeherve\RSSReplyViaEmail
 */

declare( strict_types = 1 );

namespace Jeherve\RSSReplyViaEmail;

use WP_Post;

defined( 'ABSPATH' ) || exit;

add_action( 'atom_author', __NAMESPACE__ . '\add_email_to_atom_feeds' );
add_action( 'rss_head', __NAMESPACE__ . '\add_email_to_rss_feeds' );
add_action( 'rss2_head', __NAMESPACE__ . '\add_email_to_rss_feeds' );

/**
 * Display my account's email address in Atom feeds.
 *
 * @since 1.0.0
 *
 * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements:~:text=Recommended%20feed%20elements
 *
 * @return void
 */
function add_email_to_atom_feeds(): void {
	// Get the author's email address.
	$author_email = get_author_email();

	// No info, bail.
	if ( empty( $author_email ) ) {
		return;
	}

	// Add the email address to the feed.
	echo '<email>' . esc_html( $author_email ) . '</email>';
}

/**
 * Display my account's email address in RSS feeds.
 *
 * @since 1.0.0
 *
 * @see https://www.w3schools.com/xml/rss_tag_managingeditor.asp
 *
 * @return void
 */
function add_email_to_rss_feeds(): void {
	$author_email = get_author_email();

	// No info, bail.
	if ( empty( $author_email ) ) {
		return;
	}

	// Add the email address to the feed.
	echo '<managingEditor>' . esc_html( $author_email ) . '</managingEditor>';
}

/**
 * Get a post author's email address.
 *
 * @since 1.0.0
 *
 * @return string The author's email address, or an empty string if no email address is found.
 */
function get_author_email(): string {
	$post = get_post();
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	// Get the author's email address.
	$author_email = get_the_author_meta( 'user_email', (int) $post->post_author );

	/**
	 * Filter the author's email address.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $author_email The author's email address.
	 * @param int     $author_id    The author's ID.
	 * @param WP_Post $post         The post object.
	 */
	return apply_filters(
		'rss_reply_via_email_author_email',
		$author_email,
		(int) $post->post_author,
		$post
	);
}
