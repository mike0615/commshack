<?php
// Isolated WordPress API harness; native WordPress integration is qualified separately.
define('ABSPATH', __DIR__);
$logged_in = false; $admin = false; $shortcodes = array(); $filters = array();
function add_action($name, $callback) {}
function add_shortcode($name, $callback) { global $shortcodes; $shortcodes[$name] = $callback; }
function plugin_dir_path($path) { return dirname($path) . '/'; }
function is_user_logged_in() { global $logged_in; return $logged_in; }
function wp_login_url() { return 'https://portal.test/wp-login.php'; }
function esc_html($x) { return htmlspecialchars($x, ENT_QUOTES, 'UTF-8'); }
function esc_attr($x) { return esc_html($x); }
function esc_url($x, $protocols = null) { return esc_attr($x); }
function wp_parse_url($url) { return parse_url($url); }
function apply_filters($name, $url) { global $filters; return $filters[$name] ?? $url; }
function shortcode_exists($name) { return false; }
function do_shortcode($text) { throw new Exception('Unexpected files-plugin invocation'); }
function current_user_can($cap) { global $admin; return $admin; }
function add_management_page(...$args) {}
require dirname(__DIR__) . '/plugins/commshack-shortcodes/commshack-shortcodes.php';
function check($value, $label) { if (!$value) { throw new Exception($label); } echo "PASS: $label\n"; }
commshack_register_shortcodes();
check(count($shortcodes) === 10 && $shortcodes['livewire_chat'] === 'commshack_chat_cb', 'new and legacy shortcodes registered');
foreach (array('dashboard','chat','mail','files','help','about') as $name) {
    $html = call_user_func('commshack_' . $name . '_cb');
    check(str_contains($html, 'Sign in') && !str_contains($html, '<iframe'), 'anonymous ' . $name . ' denied');
}
$logged_in = true;
check(str_contains(commshack_dashboard_cb(), 'tool-card'), 'authenticated dashboard');
check(str_contains(commshack_chat_cb(), 'https://xmpp.acc.local/converse/'), 'HTTPS chat embed');
$filters['commshack_xmpp_url'] = 'javascript:alert(1)';
check(!str_contains(commshack_chat_cb(), '<iframe'), 'unsafe embed rejected');
$filters['commshack_xmpp_url'] = 'https://user:secret@host.test/';
check(!str_contains(commshack_chat_cb(), '<iframe'), 'credential URL rejected');
$filters['commshack_xmpp_url'] = array('invalid');
check(!str_contains(commshack_chat_cb(), '<iframe'), 'non-string filter handled');
unset($filters['commshack_xmpp_url']);
$filters['livewire_xmpp_url'] = 'https://legacy.test/chat';
check(str_contains(commshack_chat_cb(), 'https://legacy.test/chat'), 'legacy URL filter retained');
check(str_contains(commshack_files_cb(), 'separately supplied'), 'missing files plugin explained');
check(str_contains(commshack_help_cb(), 'requires no internet') && str_contains(commshack_about_cb(), '1.2.0'), 'embedded help and version');
ob_start(); commshack_admin_help(); $html = ob_get_clean();
check($html === '', 'administrator help capability enforced');
$admin = true; ob_start(); commshack_admin_help(); $html = ob_get_clean();
check(str_contains($html, 'CommShack help') && str_contains($html, 'MIT license'), 'administrator Help/About available');
$path = dirname(__DIR__) . '/plugins/commshack-shortcodes/tools.json';
$original = file_get_contents($path);
try {
    file_put_contents($path, '[null,"bad",{"icon":"x","title":"x","desc":"x","url":"javascript:alert(1)"}]');
    check(commshack_load_tools() === array(), 'malformed tool definitions rejected');
    file_put_contents($path, json_encode(array(array('icon'=>'<script>', 'title'=>'<img onerror=x>', 'desc'=>'"<svg>', 'url'=>'https://host.test/'))));
    $html = commshack_dashboard_cb();
    check(!str_contains($html, '<script>') && !str_contains($html, '<img') && str_contains($html, '&lt;'), 'card content escaped');
    file_put_contents($path, '{bad');
    check(commshack_load_tools() === array(), 'invalid JSON fails closed');
} finally { file_put_contents($path, $original); }
echo "CommShack plugin harness passed.\n";
