<?php
/*
Plugin Name: Lorem Custom Button
Description: advanced button for Lorem theme
Version 1.0
Auther: Chris
*/

if( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly(security measure)

class LoremCustomBlock {
    function __construct() {
        add_action('init', array($this, 'adminAssets'));
    }
    function adminAssets() {
        wp_register_style('lorembtncss', plugin_dir_url(__FILE__) . 'build/index.css');
        wp_register_script('mynewblocktype', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-element', 'wp-editor'));
        register_block_type('myplugin/lorem-custom-block', array(
            'editor_script' => 'mynewblocktype',
            'editor_style' => 'lorembtncss',
            'render_callback' => array($this, 'theHTML')
        ));
    }
    function theHTML($attributes) {
        //output buffer, return a chunck of html this way
        ob_start(); ?>
            <?php echo "<button class='lorem-edit-btn main-btn flex-row flex-center' style='border-color: {$attributes['buttonRightColorOne']}; width: {$attributes['buttonWidth']}px; height: {$attributes['buttonHeight']}px'>"; ?>
                <?php echo "<div class='lorem-edit-btn-left flex-center' style='background-color: {$attributes['buttonLeftColor']}; font-size: {$attributes['buttonIconSize']}px; color: {$attributes['buttonIconColor']}'>"; ?>
                    <?php echo "<div class='lorem-edit-btn-triangle' style='border-left-color: {$attributes['buttonLeftColor']}'></div>"; ?>
                    <?php echo "<span class='{$attributes['buttonIcon']}'></span>"; ?>
                </div>
                <?php echo "<div class='lorem-edit-btn-right flex-center' style='background: linear-gradient({$attributes['buttonRightColorTwo']}, {$attributes['buttonRightColorOne']}); font-size: {$attributes['buttonFontSize']}px; color: {$attributes['buttonFontColor']}'>{$attributes['buttonText']}</div>"; ?>
            </button>
        <?php return ob_get_clean();
    }
}

$testBlock1 = new LoremCustomBlock();