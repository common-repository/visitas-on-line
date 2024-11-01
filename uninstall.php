<?php
/**
 * Comandos para borrar todas las extructuras creadas
 *
 * @package Visitas on-line
 * @version 1.0
 * @Author: Nelson Diaz
 * @Author URI: http://nelsondiazrodriguez.tk/
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

$nombre_tabla = $wpdb->prefix . 'visitas';

$sql = 'DROP table IF EXISTS ' . $nombre_tabla;
$wpdb->query( $sql );
$wpdb->flush();

// pendiente: agregar para borrar página "visitas" que incluye el shortcode: [prueba].

