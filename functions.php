<?php

function theme_enqueue_assets() {
  // Versión del tema activo (si usas child theme, será la del child)
  $theme   = wp_get_theme();
  $version = $theme->get('Version');

  // CSS
  wp_enqueue_style(
    'child-style',
    get_stylesheet_uri(),      // style.css del tema activo
    [],
    $version
  );

  wp_enqueue_style(
    'main-css',
    get_stylesheet_directory_uri() . '/dist/styles/main.css',
    [],
    $version
  );

  // JS
  wp_enqueue_script(
    'backgrounds-js',
    get_stylesheet_directory_uri() . '/dist/scripts/backgrounds.js',
    [],
    $version,
    true
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


$avada_options = get_option( 'fusion_options' );



$customFonts = [
  'Primary - Header title',
  'Primary - Header Subtitle',
  'Secondary - Content title',
  'Secondary - Content text',
  'Tertiary - Content title',
  'Tertiary - Content text',
  'Forms, Navs and others',
];

$foundFonts = [];
foreach ($customFonts as $fontLabel) {
  $found = null;
  if (isset($avada_options['typography_sets']) && is_array($avada_options['typography_sets'])) {
    foreach ($avada_options['typography_sets'] as $set) {
      if (isset($set['label']) && $set['label'] === $fontLabel) {
        $found = $set;
        break;
      }
    }
  }
  $foundFonts[$fontLabel] = $found ? $found : [];
}

// Mapeo de labels a clases CSS
$fontClassMap = [
  'Primary - Header title'      => ['.header-title'],
  'Primary - Header Subtitle'   => ['.header-subTitle'],
  'Secondary - Content title'   => ['.content-title', 'li.post-card .fusion-title-heading a'],
  'Secondary - Content text'    => ['.content-text'],
  'Tertiary - Content title'    => ['.third-content-title'],
  'Tertiary - Content text'     => ['.third-content-text'],
  'Forms, Navs and others'      => ['forms-navs-others'], // Manejo especial abajo
];

// Construir CSS dinámico
$customCss = '';
foreach ($foundFonts as $label => $fontData) {
  if (!empty($fontData) && isset($fontClassMap[$label]) && !empty($fontData['font-family'])) {
    $selectors = $fontClassMap[$label];
    $fontFamily = $fontData['font-family'];
    $fontWeight = isset($fontData['variant']) ? $fontData['variant'] : 'normal';

    // Caso especial para "Forms, Navs and others"
    if ($selectors === ['forms-navs-others']) {
      $customCss .= "html body div,\n";
      $customCss .= "html body h1,\n";
      $customCss .= "html body h2,\n";
      $customCss .= "html body h3,\n";
      $customCss .= "html body h4,\n";
      $customCss .= "html body h5,\n";
      $customCss .= "html body h6,\n";
      $customCss .= "html body p,\n";
      $customCss .= "html body a,\n";
      $customCss .= "html body span,\n";
      $customCss .= "html body button,\n";
      $customCss .= "html body label,\n";
      $customCss .= "html body input,\n";
      $customCss .= "html body textarea,\n";
      $customCss .= "html body select\n";
      $customCss .= " { font-family: {$fontFamily};  }\n";
      $customCss .= "html body .font-others,\n";
      $customCss .= "html body .font-others *\n";
      $customCss .= " { font-family: {$fontFamily} !important;  }\n";

    } else {
      // Generar CSS para cada selector
      foreach ($selectors as $selector) {
        // Si el selector ya empieza con punto, # o es un selector complejo, usar tal cual
        $selectorStr = (preg_match('/^[.#]/', $selector) || strpos($selector, ' ') !== false) ? $selector : ".{$selector}";
        $customCss .= "html body {$selectorStr},\n";
        $customCss .= "html body {$selectorStr} * { font-family: {$fontFamily} !important; font-weight: {$fontWeight} !important; }\n";
      }
    }
  }
}

if (!empty($customCss)) {
  add_action('wp_head', function() use ($customCss) {
    echo "<style id='custom-fonts-css'>\n{$customCss}</style>\n";
  }, 0);
}

  // echo '<pre style="background:#fff; color:#222; padding:1em; border:1px solid #ccc;">';
  // print_r($foundFonts);
  // echo '</pre>';