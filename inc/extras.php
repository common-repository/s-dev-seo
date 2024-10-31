<?php

/**
 * Recursive sanitation for an array
 * 
 * @param $array
 *
 * @return mixed
 */
function wpse_sdevseo_recursive_sanitize_text_field($array) {
    foreach ( $array as $key => &$value ) {
        if ( is_array( $value ) ) {
            $value = wpse_sdevseo_recursive_sanitize_text_field($value);
        }
        else {
            $value = sanitize_text_field( $value );
        }
    }

    return $array;
}