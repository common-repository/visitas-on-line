<?php
/**
 * Plugin Name: Visitas On-Line
 * Plugin URI: https://nelsondiaz.xyz/pluginswp-visitas-on-line
 * Description: Visitas on-line.
 * Version: 1.0
 * Author: Nelson Díaz
 * Author URI: https://nelsondiaz.xyz
 * Text Domain: visitas
 * Domain Path: /languages
 *
 * @package visitas on line
 */

defined( 'ABSPATH' ) || exit;

define( 'VISITAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once VISITAS_PLUGIN_DIR . '/includes/ajustes.php';

add_action( 'after_setup_theme', 'visitas_plugin_traduciones' );
/**
 * Cargar traduciones
 */
function visitas_plugin_traduciones() {
	load_plugin_textdomain( 'visitas', false, VISITAS_PLUGIN_DIR . '/languages/' );
}

register_activation_hook( __FILE__, 'visitas_plugin_crear_tablas' );
/**
 * Crear la tabla 'visitas' al activar el plugins
 */
function visitas_plugin_crear_tablas() {
	global $wpdb;
	$nombre_tabla    = $wpdb->prefix . 'visitas';
	$charset_collate = $wpdb->get_charset_collate();
	$sql             = "CREATE TABLE if not exists $nombre_tabla (id mediumint(9) NOT NULL AUTO_INCREMENT, 
		ip varchar(40) NOT NULL DEFAULT '', 
		detalle varchar(50) NOT NULL DEFAULT '', 
		fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), 
		pais varchar(2) NOT NULL DEFAULT '', 
		de_donde varchar(50) NOT NULL DEFAULT '', 
		fecha_server timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), 
		UNIQUE KEY (id)) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	$wpdb->flush();
	// Crear pagina: page-visitas (ver visitas desde el front-end).
	wp_insert_post(
		array(
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_title'    => 'Visitas',
			'post_content'  => '[prueba]',
			'post_category' => array(),
		)
	);
}
/**
 * Función Principal
 * Se obtienen las variables:
 * $demo        : Se utiliza para testear el funcionamiento total de plugins
 * $ip1         : IP del usuario
 * $url         : Página solicitada
 * $viene       : Agente de usuario correspondiente
 * $codigo_error: Adjuntar a la variable [$url] si corresponde, su código de error: 404, 403, 410
 */
function visitas_plugin_registro() {
	global $wpdb, $ip1, $url, $viene, $codigo_error; // Se quito $fecha,$nuevaFecha.

	$ip1          = sanitize_text_field( $ip1 );
	$url          = filter_input( INPUT_SERVER, 'REQUEST_URI' );
	$viene        = sanitize_text_field( $viene );
	$codigo_error = sanitize_text_field( $codigo_error );
	$nombre_tabla = $wpdb->prefix . 'visitas';

	if ( isset( $codigo_error ) ) {
		$url = $url . $codigo_error;
	}
	if ( isset( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) {
		// Usando servicio CloudFlare.
		$pais         = filter_input( INPUT_SERVER, 'HTTP_CF_IPCOUNTRY' );
		$country_code = wp_unslash( $pais );
		// A $_SESSION['userPAIS'] = $country_code;.
	} else {
		// Normal.
		require_once VISITAS_PLUGIN_DIR . '/includes/geoip.inc';
		$abir_bd      = geoip_open( VISITAS_PLUGIN_DIR . '/includes/GeoIP.dat', GEOIP_STANDARD );
		$country_code = geoip_country_code_by_addr( $abir_bd, $ip1 );
		geoip_close( $abir_bd );
	}
	if ( ! isset( $country_code ) ) {
		$country_code = 'XX';
	}
	$wpdb->insert(
		$nombre_tabla,
		array(
			'ip'           => $ip1,
			'detalle'      => $url,
			'fecha'        => $nueva_fecha,
			'pais'         => $country_code,
			'de_donde'     => $viene,
			'fecha_server' => $fecha,
		)
	);
}
add_action( 'wp_footer', 'visitas_plugin_registro' );
// add_action('admin_footer', 'visitas_plugin_registro'); registrar movimiento en el escritorio.

/**
 * Acerar todos los datos registrados al desactivar el plugin
 */
function visitas_plugin_acerar_tablas() {
	global $wpdb;
	$nombre_tabla = $wpdb->prefix . 'visitas';
	$wpdb->query( 'truncate table ' . $nombre_tabla );
	$wpdb->flush();
}
register_deactivation_hook( __FILE__, 'visitas_plugin_acerar_tablas' );
