<?php
/**
 * Activar todas los ajustes necesarios
 *
 * @package visitas on line
 */

defined( 'ABSPATH' ) || exit;
/**
 * Crear opciones para el meún del plugins.
 */
function visitas_plugin_agregar_pagina_menu() {
	add_menu_page(
		__( 'Visitas On-Line', 'visitas' ),
		__( 'Visitas On-Line', 'visitas' ),
		'manage_options',
		'visitas-settings',
		'visitas_plugin_mostrar_pagina_visitas'
	);
	add_submenu_page(
		'visitas-settings',
		__( 'Configuración', 'visitas' ),
		__( 'Configuración', 'visitas' ),
		'manage_options',
		'visitas_options_submenu',
		'visitas_plugin_mostrar_pagina_config'
	);
}
add_action( 'admin_menu', 'visitas_plugin_agregar_pagina_menu' );
/**
 * Mostrar las visitas registradas.
 */
function visitas_plugin_mostrar_pagina_visitas() {
	require_once VISITAS_PLUGIN_DIR . 'paginas/mostrar-visitas-page.php';
}
/**
 * Mostrar las opciones de configuración.
 */
function visitas_plugin_mostrar_pagina_config() {
	require_once VISITAS_PLUGIN_DIR . 'paginas/mostrar-ajustes-page.php';
}
/**
 * Cargar la hoja de estilos.
 */
function visitas_plugin_agregar_hoja_estilos() {
	//wp_register_style( 'visitas-style', plugins_url( '/css/plugin-style.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_style( 'visitas-style', plugins_url( '../css/plugin-style.css', __FILE__ ), array(), '1.0', 'all' );
}
add_action( 'admin_enqueue_scripts', 'visitas_plugin_agregar_hoja_estilos' );
/**
 * Obtener la ip del visitante.
 */
function visitas_plugin_real_ip() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED',
        'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}
$ip1 = visitas_plugin_real_ip();
/**
 * Traducir los agentes de usuarios a texto compresible
 */
$viene = '';

if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Googlebot' ) !== false ) $viene              = 'googlebot';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Googlebot-Image' ) !== false ) $viene        = 'googlebot-image';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Mediapartners-Google' ) !== false ) $viene   = 'google-adsense';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Mediapartners' ) !== false ) $viene          = 'google-adsense';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'AdsBot-Google' ) !== false) $viene           = 'google-adsbot';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Google-AdWords-Express' ) !== false ) $viene = 'google-adsbot';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Googlebot-News' ) !== false ) $viene         = 'google-new';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'msnbot' ) !== false ) $viene                 = 'MSNBot';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'bingbot' ) !== false ) $viene                = 'bingbot';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'msnbot-media' ) !== false ) $viene           = 'MSNBot-Media';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), '"adidxbot' ) !== false ) $viene              = 'AdidxBot';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'BingPreview' ) !== false ) $viene            = 'BingPreview';
if ( strpos( filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' ), 'Scooter' ) !== false ) $viene                = 'altavista';

/**
 * Validar la variable "fecha".
 *
 * @$una_fecha.
 */
function visitas_plugin_validar_fecha( $una_fecha ) {
	$valores = explode( '-', $una_fecha );
	if ( count( $valores ) === 3 && checkdate( $valores[1], $valores[0], $valores[2] ) ) {
		return true;
	} else {
		return false;
	}
}
