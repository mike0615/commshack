<?php
/**
 * CommShack Child Theme functions
 *
 * Shortcodes (livewire_dashboard, livewire_chat, livewire_mail, livewire_files)
 * are owned by the commshack-shortcodes plugin. Do not re-register them here.
 */
if (!defined('ABSPATH')) { exit; }
function commshack_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('commshack-child', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), '1.2.0');
}
add_action('wp_enqueue_scripts', 'commshack_enqueue_styles');

