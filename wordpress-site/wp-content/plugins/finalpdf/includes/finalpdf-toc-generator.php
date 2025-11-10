<?php
/**
 * Table of Contents Generator for FinalPDF
 *
 * This file contains functions for automatically generating
 * Table of Contents from post/page content headings.
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/includes
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

if ( ! function_exists( 'finalpdf_generate_toc' ) ) {

        /**
         * Generate Table of Contents from HTML content
         *
         * @param string $html The HTML content to parse for headings.
         * @param array  $args Optional arguments for TOC generation.
         * @return array Array with 'toc_html' and 'content_html' with anchors.
         */
        function finalpdf_generate_toc( $html, $args = array() ) {
                
                $defaults = array(
                        'enabled'    => true,
                        'max_depth'  => 6,     // Maximum heading level to include (1-6)
                        'min_depth'  => 1,     // Minimum heading level to include
                        'title'      => __( 'Table of Contents', 'finalpdf' ),
                );
                
                $args = wp_parse_args( $args, $defaults );
                
                if ( ! $args['enabled'] ) {
                        return array(
                                'toc_html'     => '',
                                'content_html' => $html,
                        );
                }
                
                // Parse HTML and extract headings
                $headings = finalpdf_extract_headings( $html, $args['min_depth'], $args['max_depth'] );
                
                if ( empty( $headings ) ) {
                        return array(
                                'toc_html'     => '',
                                'content_html' => $html,
                        );
                }
                
                // Add anchor IDs to headings in the content
                $content_with_anchors = finalpdf_add_heading_anchors( $html, $headings );
                
                // Generate TOC HTML
                $toc_html = finalpdf_build_toc_html( $headings, $args['title'] );
                
                return array(
                        'toc_html'     => $toc_html,
                        'content_html' => $content_with_anchors,
                );
        }
        
        /**
         * Extract headings from HTML content
         *
         * @param string $html      The HTML content.
         * @param int    $min_depth Minimum heading level.
         * @param int    $max_depth Maximum heading level.
         * @return array Array of headings with text, level, and slug.
         */
        function finalpdf_extract_headings( $html, $min_depth = 1, $max_depth = 6 ) {
                $headings = array();
                
                // Create heading pattern for specified depth range
                $pattern = '/<h([' . $min_depth . '-' . $max_depth . '])[^>]*>(.*?)<\/h\1>/is';
                
                if ( preg_match_all( $pattern, $html, $matches, PREG_SET_ORDER ) ) {
                        $slug_registry = array(); // Global slug tracker to prevent duplicates
                        
                        foreach ( $matches as $match ) {
                                $level = (int) $match[1];
                                $text  = strip_tags( $match[2] );
                                $text  = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );
                                $text  = trim( $text );
                                
                                if ( empty( $text ) ) {
                                        continue;
                                }
                                
                                // Generate globally unique slug
                                $slug = finalpdf_generate_heading_slug( $text, 1 );
                                
                                // Ensure global uniqueness
                                $counter = 1;
                                while ( isset( $slug_registry[ $slug ] ) ) {
                                        $counter++;
                                        $slug = finalpdf_generate_heading_slug( $text, $counter );
                                }
                                $slug_registry[ $slug ] = true;
                                
                                $headings[] = array(
                                        'level'    => $level,
                                        'text'     => $text,
                                        'slug'     => $slug,
                                        'original' => $match[0],
                                );
                        }
                }
                
                return $headings;
        }
        
        /**
         * Generate a URL-safe slug from heading text
         *
         * @param string $text    The heading text.
         * @param int    $counter Counter for duplicate headings.
         * @return string The generated slug.
         */
        function finalpdf_generate_heading_slug( $text, $counter = 1 ) {
                // Remove special characters and convert to lowercase
                $slug = sanitize_title_with_dashes( $text );
                
                // Add counter if not first occurrence
                if ( $counter > 1 ) {
                        $slug .= '-' . $counter;
                }
                
                return $slug;
        }
        
        /**
         * Add anchor IDs to headings in HTML content
         *
         * @param string $html     The HTML content.
         * @param array  $headings Array of headings with slugs.
         * @return string Modified HTML with anchors.
         */
        function finalpdf_add_heading_anchors( $html, $headings ) {
                $modified_html = $html;
                
                foreach ( $headings as $heading ) {
                        $original = $heading['original'];
                        $slug     = $heading['slug'];
                        $level    = $heading['level'];
                        
                        // Add ID to the heading tag
                        $modified = preg_replace(
                                '/<h' . $level . '([^>]*)>/i',
                                '<h' . $level . '$1 id="' . esc_attr( $slug ) . '">',
                                $original,
                                1
                        );
                        
                        // Replace in HTML (only first occurrence)
                        $pos = strpos( $modified_html, $original );
                        if ( $pos !== false ) {
                                $modified_html = substr_replace( $modified_html, $modified, $pos, strlen( $original ) );
                        }
                }
                
                return $modified_html;
        }
        
        /**
         * Build the TOC HTML structure using nested ordered lists
         *
         * @param array  $headings  Array of headings.
         * @param string $toc_title Title for the TOC section.
         * @return string The TOC HTML.
         */
        function finalpdf_build_toc_html( $headings, $toc_title ) {
                if ( empty( $headings ) ) {
                        return '';
                }
                
                $html = '<div class="finalpdf-toc" style="page-break-after: always; margin-bottom: 30px; padding: 20px; border: 1px solid #ddd;">';
                $html .= '<h2 class="finalpdf-toc-title" style="font-size: 24px; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; color: #333;">' . esc_html( $toc_title ) . '</h2>';
                
                // Get first heading level to use as baseline
                $first_level = $headings[0]['level'];
                $current_level = $first_level - 1;
                $html .= '<ol class="finalpdf-toc-list" style="list-style-type: decimal; margin-left: 20px; line-height: 1.8; padding-left: 10px;">';
                
                foreach ( $headings as $index => $heading ) {
                        $level = $heading['level'];
                        $text  = esc_html( $heading['text'] );
                        $slug  = esc_attr( $heading['slug'] );
                        
                        // Calculate relative depth from first heading
                        $relative_depth = $level - $first_level;
                        
                        // Close nested lists if we're going back up
                        while ( $current_level > $level ) {
                                $html .= '</ol></li>';
                                $current_level--;
                        }
                        
                        // Open nested lists if we're going deeper
                        while ( $current_level < $level ) {
                                if ( $current_level >= $first_level ) {
                                        $html .= '<ol style="list-style-type: lower-alpha; margin-left: 20px; padding-left: 10px;">';
                                }
                                $current_level++;
                        }
                        
                        // Add TOC entry
                        $html .= '<li style="margin: 6px 0;">';
                        $html .= '<a href="#' . $slug . '" style="color: #2271b1; text-decoration: none;">';
                        $html .= '<span class="finalpdf-toc-text">' . $text . '</span>';
                        $html .= '</a>';
                        
                        // Check if next heading is deeper (will open nested list)
                        if ( isset( $headings[ $index + 1 ] ) && $headings[ $index + 1 ]['level'] > $level ) {
                                // Keep <li> open for nested list
                        } else {
                                $html .= '</li>';
                        }
                }
                
                // Close all remaining open lists
                while ( $current_level >= $first_level ) {
                        if ( $current_level > $first_level ) {
                                $html .= '</ol></li>';
                        }
                        $current_level--;
                }
                
                $html .= '</ol>'; // Close main list
                $html .= '</div>'; // .finalpdf-toc
                
                return $html;
        }
}

/**
 * Get TOC settings from WordPress options
 *
 * @return array TOC settings.
 */
function finalpdf_get_toc_settings() {
        $toc_settings = get_option( 'finalpdf_toc_settings', array() );
        
        $defaults = array(
                'enable_toc'  => 'yes',
                'toc_depth'   => '6',
                'toc_title'   => __( 'Table of Contents', 'finalpdf' ),
        );
        
        return wp_parse_args( $toc_settings, $defaults );
}
