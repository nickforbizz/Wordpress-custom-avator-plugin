<?php
/**
 * Plugin Name: KEMRI Avatar 
 * Plugin URI: supertechnomads.com/wordpress#plugins
 * Description: A plugin to handle custom user avatar uploads with a fallback to Gravatar.
 * Version: 1.0.0
 * Requires PHP: 7^
 * Author: Nicholas Waruingi
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Autoload necessary classes
spl_autoload_register(function ($class) {
    $prefix = 'CustomAvatar\\';
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
function custom_avatar_plugin_init() {
    $avatar_handler = new CustomAvatar\Avatar_Handler();
    $avatar_handler->init();
}
add_action('plugins_loaded', 'custom_avatar_plugin_init');
