<?php
/**
 * Mostrar los ajuste que se pueden modificar
 *
 * @package visitas on line
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="izq">
	<img src="<?php echo esc_url_raw( plugin_dir_url( __DIR__ ) . 'assets/banner-772x250.png' ); ?>" width="580">
	<p><b>«<?php esc_attr_e( 'Visitas on line', 'visitas' ); ?>»</b> <?php esc_attr_e( 'se caracteriza por mostrar toda la actividad que realiza una ip, en nuestra web. Cada visita es registrada con los siguientes datos: ', 'visitas' ); ?><i><?php esc_attr_e( 'ip, página visitada, día y hora, país y referencia de donde proviene', 'visitas' ); ?></i>.
	<?php esc_attr_e( 'Internamente funciona gracias a la base de datos de ', 'visitas' ); ?><a href="https://maxmind.com">MAXMIND</a><?php esc_attr_e( ' y es compatible con ', 'visitas' ); ?><a href="https://cloudflare.com">CLOUDFLARE</a>.</p>
</div>
<div id="der">
</div>
<div style="clear:left"></div>
<h2><?php esc_attr_e( 'Configuración de la Zona Horaria', 'visitas' ); ?></h2>
<?php esc_attr_e( 'Zona Horaria Actual: ', 'visitas' ); ?><b> <?php echo esc_attr( get_option( 'timezone_string' ) ); ?></b><br>
<p><?php esc_attr_e( 'Si tu Zona Horaria NO es correcta (para modificarla), deberás realizarlo en: ', 'visitas' ); ?><a href="/wp-admin/options-general.php">
<?php esc_attr_e( 'Ajustes => Generales => Zona horaria', 'visitas' ); ?></a>, <?php esc_attr_e( 'del Menú Lateral de tu Escritorio.', 'visitas' ); ?><br> <b><?php esc_attr_e( 'Captura de Pantalla:', 'visitas' ); ?></b></p> 
<img src="<?php echo esc_url_raw( plugin_dir_url( __DIR__ ) . 'assets/ajuste-time-zone.png' ); ?>" width="780">
