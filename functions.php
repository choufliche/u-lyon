<?php

add_action( 'wp_enqueue_scripts', 'wpm_enqueue_styles' );

function wpm_enqueue_styles() {
	wp_enqueue_style( 
		'enfant-style', 
		get_stylesheet_uri()
	);
}