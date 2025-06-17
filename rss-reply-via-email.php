<?php
/**
 * Plugin Name: RSS Reply via email
 * Plugin URI: https://herve.bzh/my-plugins/rss-reply-via-email/
 * Description: Add a reply-to email address for each post in your RSS feeds.
 * Author: Jeremy Herve
 * Author URI: https://herve.bzh/
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
use WP_User;

defined( 'ABSPATH' ) || exit;

add_action( 'atom_author', __NAMESPACE__ . '\add_email_to_atom_feeds' );
add_action( 'rss2_item', __NAMESPACE__ . '\add_email_to_rss_feeds' );

/**
 * Display my account's email address in Atom feeds.
 *
 * @since 1.0.0
 *
 * @see https://validator.w3.org/feed/docs/atom.html#person
 *
 * @return void
 */
function add_email_to_atom_feeds(): void {
	$author_info = get_author_info();

	// No info, bail.
	if ( empty( $author_info['email'] ) ) {
		return;
	}

	// Add the email address to the feed.
	echo '<email>qu' . esc_xml( $author_info['email'] ) . '</email>';
}

/**
 * Display my account's email address in RSS feeds.
 * The field expects an email address, followed by a name between parentheses.
 *
 * @since 1.0.0
 *
 * @see https://www.rssboard.org/rss-specification#ltauthorgtSubelementOfLtitemgt
 * @see https://validator.w3.org/feed/docs/warning/MissingRealName.html
 *
 * @return void
 */
function add_email_to_rss_feeds(): void {
	$author_info = get_author_info();

	// No info, bail.
	if ( empty( $author_info['email'] ) || empty( $author_info['name'] ) ) {
		return;
	}

	printf(
		'<author>%1$s (%2$s)</author>',
		esc_xml( $author_info['email'] ),
		esc_xml( $author_info['name'] )
	);
}

/**
 * Get a post author's email address.
 *
 * @since 1.0.0
 *
 * @see https://developer.wordpress.org/reference/functions/get_author_info/
 *
 * @return array Array of author info (email address and name).
 */
function get_author_info(): array {
	$post = get_post();
	if ( ! $post instanceof WP_Post ) {
		return array();
	}

	$user_info = get_user_by( 'id', (int) $post->post_author );
	if ( ! $user_info instanceof WP_User ) {
		return array();
	}

	$author_info = array(
		'email' => $user_info->user_email,
		'name'  => $user_info->display_name,
	);

	/**
	 * Filter the author's email address.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $author_info  The author's email address and name.
	 * @param int     $author_id    The author's ID.
	 * @param WP_Post $post         The post object.
	 */
	return apply_filters(
		'rss_reply_via_email_author_info',
		$author_info,
		(int) $post->post_author,
		$post
	);
}
