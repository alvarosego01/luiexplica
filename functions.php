<?php

function theme_enqueue_assets() {
  // Versión del tema activo (si usas child theme, será la del child)
  $theme   = wp_get_theme();
  $version = $theme->get('Version');

  $google_fonts_url = 'https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap';

  // Enqueue Google Fonts stylesheet
  wp_enqueue_style(
    'google-fonts',
    $google_fonts_url,
    [],
    null
  );

  // Añadir enlaces de preconnect en head (incluye crossorigin para fonts.gstatic.com)
  add_action('wp_head', function() {
    if (wp_style_is('google-fonts', 'queue')) {
      echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . PHP_EOL;
      echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . PHP_EOL;
    }
  }, 1);

  // CSS
  wp_enqueue_style(
    'child-style',
    get_stylesheet_uri(),
    [],
    $version
  );

  wp_enqueue_style(
    'main-css',
    get_stylesheet_directory_uri() . '/dist/styles/main.css',
    [],
    $version
  );

  wp_enqueue_script(
    'main-js',
    get_stylesheet_directory_uri() . '/dist/scripts/main.js',
    [],
    $version,
    true
  );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets', 20);

function avada_lang_setup()
{
  $lang = get_stylesheet_directory() . '/languages';
  load_child_theme_textdomain('Avada', $lang);
}
add_action('after_setup_theme', 'avada_lang_setup');


add_action('init', 'enable_testimonial_translation_polylang');

function enable_testimonial_translation_polylang() {
    // Verifica si Polylang está activo
    if (function_exists('pll_register_string')) {

        // 1. Registrar el CPT "testimonial" para traducción
        if (post_type_exists('testimonial')) {

            // Opción A: Habilitar traducción desde código (Polylang Pro)
            $polylang_options = get_option('polylang');

            if (is_array($polylang_options)) {
                // Añade "testimonial" a los post types traducibles
                if (!isset($polylang_options['post_types']) || !in_array('testimonial', $polylang_options['post_types'])) {
                    $polylang_options['post_types'][] = 'testimonial';
                    update_option('polylang', $polylang_options);
                }
            }

            // Opción B: Forzar registro (alternativa)
            add_filter('pll_get_post_types', 'add_testimonial_to_polylang', 10, 2);
            function add_testimonial_to_polylang($post_types, $is_settings) {
                if ($is_settings) {
                    $post_types['testimonial'] = 'testimonial';
                }
                return $post_types;
            }
        }
    }
}

