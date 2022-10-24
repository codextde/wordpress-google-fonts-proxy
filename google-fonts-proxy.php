<?php
/*
Plugin Name: Google Fonts Proxy
Version: 1.0.0
Plugin URI: https://codext.de
Description: Proxy for Google Fonts
Author: #
Author URI: https://codext.de
License: MIT
Text Domain: google-fonts-proxy
*/


if (!defined('ABSPATH')) {
	exit;
}


function remove_dns_prefetch($urls, $relation_type) {
	if ('dns-prefetch' === $relation_type) {
		$urls = array_diff($urls, array('fonts.googleapis.com'));
	} elseif ('preconnect' === $relation_type) {
		foreach ($urls as $key => $url) {
			if (!isset($url['href'])) {
				continue;
			}
			if (preg_match('/\/\/fonts\.(gstatic|googleapis)\.com/', $url['href'])) {
				unset($urls[$key]);
			}
		}
	}

	return $urls;
}

function far_ob_call($buffer) {
	$buffer = preg_replace("fonts.gstatic.com", "google-fonts.codext.de", $buffer);
	$buffer = preg_replace("fonts.googleapis.com", "google-fonts.codext.de", $buffer);
	$buffer = str_replace("fonts.gstatic.com", "google-fonts.codext.de", $buffer);
	$buffer = str_replace("fonts.googleapis.com", "google-fonts.codext.de", $buffer);
	return $buffer;
}

function far_template_redirect() {
	ob_start();
	ob_start('far_ob_call');
}

add_filter('wp_resource_hints', 'remove_dns_prefetch', PHP_INT_MAX, 2);
add_action('template_redirect', 'far_template_redirect');