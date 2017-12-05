<?php
if (!is_null(session_get('error')) && is_array(session_get('error'))) {
    $string = '<ul class="error">';
    foreach (session_get('error') as $error) {
        $string .= "<li>{$error}</li>";
    }
    $string .= '</ul>';

    echo $string;
}