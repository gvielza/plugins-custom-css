<?php
/**
 * Plugin Name: Mi Custom CSS
 * Description: Carga y permite editar CSS personalizado desde el admin de WordPress.
 * Version: 1.1.0
 * Author: Gionnelly Vielza Durán
 */

if (!defined('ABSPATH')) exit;

define('MCC_VERSION', '1.1.0');
define('MCC_URL', plugin_dir_url(__FILE__));
define('MCC_PATH', plugin_dir_path(__FILE__));
define('MCC_CSS_FILE', MCC_PATH . 'assets/custom.css');


function mcc_enqueue_styles() {
  $ver = file_exists(MCC_CSS_FILE) ? filemtime(MCC_CSS_FILE) : MCC_VERSION;

  wp_enqueue_style(
    'mi-custom-css',
    MCC_URL . 'assets/custom.css',
    array(),
    $ver
  );
}
add_action('wp_enqueue_scripts', 'mcc_enqueue_styles');

add_action('admin_menu', function () {
  add_options_page(
    'Mi Custom CSS',
    'Mi Custom CSS',
    'manage_options',
    'mi-custom-css',
    'mcc_admin_page'
  );
});


function mcc_admin_page() {
  if (!current_user_can('manage_options')) return;

  if (isset($_POST['mcc_css'])) {
    check_admin_referer('mcc_save_css');
    file_put_contents(MCC_CSS_FILE, wp_unslash($_POST['mcc_css']));
    echo '<div class="updated"><p>CSS guardado correctamente.</p></div>';
  }

  $css = file_exists(MCC_CSS_FILE) ? file_get_contents(MCC_CSS_FILE) : '';
  ?>
  <div class="wrap">
    <h1>Mi Custom CSS</h1>
    <form method="post">
      <?php wp_nonce_field('mcc_save_css'); ?>
      <textarea name="mcc_css" style="width:100%;height:420px;font-family:monospace;"><?php echo esc_textarea($css); ?></textarea>
      <p>
        <button class="button button-primary">Guardar CSS</button>
      </p>
    </form>
  </div>
  <?php
}
