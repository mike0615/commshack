<?php
/**
 * Plugin Name: CommShack Shortcodes
 * Description: Authenticated communications dashboard, embeds, help and about.
 * Version: 1.2.0
 * Author: Mike Anderson
 * License: MIT
 * Requires at least: 6.4
 * Requires PHP: 8.0
 */
if (!defined('ABSPATH')) { exit; }
define('COMMSHACK_VERSION', '1.2.0');
function commshack_login_message() {
    return '<p><a href="' . esc_url(wp_login_url()) . '">Sign in to CommShack</a></p>';
}
function commshack_load_tools() {
    $path = plugin_dir_path(__FILE__) . 'tools.json';
    $raw = is_readable($path) ? file_get_contents($path) : false;
    $tools = $raw === false ? null : json_decode($raw, true);
    if (!is_array($tools)) { return array(); }
    $valid = array();
    foreach (array_slice($tools, 0, 100) as $tool) {
        if (!is_array($tool)) { continue; }
        foreach (array('icon', 'title', 'desc', 'url') as $field) {
            if (!isset($tool[$field]) || !is_string($tool[$field])) { continue 2; }
        }
        $url = wp_parse_url($tool['url']);
        if (!is_array($url) || ($url['scheme'] ?? '') !== 'https' || empty($url['host']) || isset($url['user']) || isset($url['pass'])) { continue; }
        $valid[] = $tool;
    }
    return $valid;
}
function commshack_dashboard_cb() {
    if (!is_user_logged_in()) { return commshack_login_message(); }
    $html = '<div class="portal-dashboard">';
    foreach (commshack_load_tools() as $tool) {
        $html .= '<a class="tool-card" href="' . esc_url($tool['url'], array('https')) . '"><h3>' . esc_html($tool['icon'] . ' ' . $tool['title']) . '</h3><p>' . esc_html($tool['desc']) . '</p></a>';
    }
    return $html . '</div>';
}
function commshack_embed($kind) {
    if (!is_user_logged_in()) { return commshack_login_message(); }
    $suffix = $kind === 'chat' ? 'XMPP' : 'MAIL';
    $name = 'COMMSHACK_' . $suffix . '_URL';
    $old = 'LIVEWIRE_' . $suffix . '_URL';
    $default = $kind === 'chat' ? 'https://xmpp.acc.local/converse/' : 'https://mail.acc.local/roundcube/';
    $url = defined($name) ? constant($name) : (defined($old) ? constant($old) : $default);
    $url = apply_filters('livewire_' . strtolower($suffix) . '_url', $url);
    $url = apply_filters('commshack_' . strtolower($suffix) . '_url', $url);
    $parts = is_string($url) ? wp_parse_url($url) : false;
    if (!is_array($parts) || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
        return '<p>CommShack embeds require a valid HTTPS URL without credentials.</p>';
    }
    return '<iframe class="embed-frame" referrerpolicy="same-origin" src="' . esc_url($url, array('https')) . '" title="' . esc_attr(ucfirst($kind)) . '"></iframe>';
}
function commshack_chat_cb() { return commshack_embed('chat'); }
function commshack_mail_cb() { return commshack_embed('mail'); }
function commshack_files_cb() {
    if (!is_user_logged_in()) { return commshack_login_message(); }
    return shortcode_exists('shared_files') ? do_shortcode('[shared_files]') : '<p>Configure the separately supplied file-sharing plugin before using Files.</p>';
}
function commshack_help_content() {
    return '<h2>CommShack help</h2><p>Use [commshack_dashboard] for tool cards, [commshack_chat] for chat, [commshack_mail] for mail and [commshack_files] for your configured file service. Sign in to view portal components.</p>'
        . '<p>Edit tools.json in the plugin directory for HTTPS destinations. Set COMMSHACK_XMPP_URL and COMMSHACK_MAIL_URL in protected WordPress configuration, or use commshack_xmpp_url and commshack_mail_url filters.</p>'
        . '<p>Chat, mail, files and SSO are separate services with their own permissions. CommShack does not store their passwords or replace their access controls. Services must explicitly permit embedding; this plugin does not bypass frame protections.</p>'
        . '<p>Existing livewire_* shortcodes and URL constants/filters remain compatible. Deactivate the old plugin before activating this one. Back up WordPress and its database before migration. See the packaged installation, ICD and operations guides. This help requires no internet access.</p>';
}
function commshack_about_content() {
    return '<h2>CommShack</h2><p>Version ' . esc_html(COMMSHACK_VERSION) . ' · Communications portal integration</p><p>Author: Mike Anderson · Copyright © 2026 Mike Anderson · MIT license</p><p>Part of the Cyber Chief toolkit. Formerly LiveWire Portal.</p><p><a href="https://github.com/mike0615/commshack">Project</a> · <a href="https://www.andersoncomputer.net/">Anderson Computer</a></p>';
}
function commshack_help_cb() { return is_user_logged_in() ? commshack_help_content() : commshack_login_message(); }
function commshack_about_cb() { return is_user_logged_in() ? commshack_about_content() : commshack_login_message(); }
function commshack_register_shortcodes() {
    foreach (array('dashboard', 'chat', 'mail', 'files', 'help', 'about') as $name) {
        add_shortcode('commshack_' . $name, 'commshack_' . $name . '_cb');
        if (in_array($name, array('dashboard', 'chat', 'mail', 'files'), true)) { add_shortcode('livewire_' . $name, 'commshack_' . $name . '_cb'); }
    }
}
add_action('init', 'commshack_register_shortcodes');
function commshack_admin_help() {
    if (!current_user_can('manage_options')) { return; }
    echo '<div class="wrap">' . commshack_help_content() . commshack_about_content() . '</div>';
}
function commshack_admin_menu() {
    add_management_page('CommShack Help & About', 'CommShack Help & About', 'manage_options', 'commshack-help', 'commshack_admin_help');
}
add_action('admin_menu', 'commshack_admin_menu');
