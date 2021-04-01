<?php
/**
 * Plugin Name: 561 Remediation
 * Plugin URI: https://www.danbailey.dev
 * Description: Fixes the update problem in Wordpress 5.6.1.
 * Version: 0.2
 * Author: Dan Bailey
 * Author URI: https://www.danbailey.dev
 */


/**
 * This resolves the issues with 5.6.1 not working when trying to update custom taxonomies, pages, etc.
 * It's enacted from the solution posted at: https://core.trac.wordpress.org/ticket/52440
 * 
 */


// Add Shortcode
function wordpressversion_shortcode() {

	return get_bloginfo( 'version' );

}
add_shortcode( 'wpversion', 'wordpressversion_shortcode' );

// This is the Javascript that will fix the 5.6.1, expressed as a function
function fiveSixOneErrorResolution() {
    if (get_bloginfo ('version') == '5.6.1') {
        ?>
        <script>
        jQuery(document).ready(function($){
        
        // Check screen
        if(typeof window.wp.autosave === 'undefined')
            return;
        
        // Data Hack
        var initialCompareData = {
            post_title: $( '#title' ).val() || '',
            content: $( '#content' ).val() || '',
            excerpt: $( '#excerpt' ).val() || ''
        };

        var initialCompareString = window.wp.autosave.getCompareString(initialCompareData);

        // Fixed postChanged()
        window.wp.autosave.server.postChanged = function(){

            var changed = false;

            // If there are TinyMCE instances, loop through them.
            if ( window.tinymce ) {
                window.tinymce.each( [ 'content', 'excerpt' ], function( field ) {
                    var editor = window.tinymce.get( field );

                    if ( ! editor || editor.isHidden() ) {
                        if ( ( $( '#' + field ).val() || '' ) !== initialCompareData[ field ] ) {
                            changed = true;
                            // Break.
                            return false;
                        }
                    } else if ( editor.isDirty() ) {
                        changed = true;
                        return false;
                    }
                } );

                if ( ( $( '#title' ).val() || '' ) !== initialCompareData.post_title ) {
                    changed = true;
                }

                return changed;
            }

            return window.wp.autosave.getCompareString() !== initialCompareString;

        }
    });
    </script>
        <?php
    } else {
        ?> <!-- Not 5.6.1. Remediation unnecessary. --> <?php
    }
}

// insert code (or not, depending on version) at admin_print_footer_scripts hook
add_action( 'admin_print_footer_scripts', 'fiveSixOneErrorResolution' );
