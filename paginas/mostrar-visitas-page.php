<?php
/**
 * Mostrar los datos de la visitas regiatradas.
 *
 * @package visitas on line
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $_POST['fecha2'] ) ) {
	// Se crea variable con el valor de la fecha actual.
	$fecha2 = date_i18n( 'd-m-Y' ); // valor tomado.
} else {
	if ( ! isset( $_POST['accion'] ) || ! wp_verify_nonce( $_POST['visitas_nonce'], 'cambiar_fecha_informe' ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		// Process form data.
		$fecha2 = sanitize_option( 'date_format', $_POST['fecha2'] );
	}
	// A $fecha2=date('d-m-Y',strtotime($fecha2)); //valor ingresado.
}
$fecha  = date_i18n( get_option( 'date_format' ), current_time( 'timestamp' ) );
$hora   = date_i18n( get_option( 'time_format' ), current_time( 'timestamp' ) );
$fecha3 = date_i18n( 'Y-m-d', strtotime( $fecha2 ) );
$mes    = date_i18n( 'm' );
?>
<h1><?php esc_html_e( 'Visitas On-Line', 'visitas' ); ?></h1>
<form class="row" action="" method="post">
	<table>
	<tr>
	<td class="punteado"><b><?php esc_html_e( 'Mostrar Informe del Día:&nbsp', 'visitas' ); ?></b></td>
	<td class="punteado">
	<?php wp_nonce_field( 'cambiar_fecha_informe', 'visitas_nonce' ); ?>
	<input type="date" name="fecha2" min="2020-01-01" max="2060-01-01" value="<?php echo $fecha3; ?>" required>
	</td>
	<td class="punteado">
	<button title="<?php esc_html_e( 'Buscar Visitas', 'visitas' ); ?>" type="submit" class="btn btn-info btn-sm " name="accion" value="buscar-informe">
	<i class='fa fa-search fa-lg'></i><?php esc_html_e( ' Buscar', 'visitas' ); ?></button>
	</td>
	</tr>
	</table>
</form>
<?php
global $wpdb;
$sql = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'visitas WHERE fecha LIKE %s order by fecha asc', '%' . $wpdb->esc_like( $fecha3 ) . '%' );
$sql1 = $wpdb->prepare( 'SELECT ip, count(*) as cantidad,pais,de_donde FROM ' . $wpdb->prefix . 'visitas WHERE fecha LIKE %s group by ip order by cantidad desc', '%' . $wpdb->esc_like( $fecha3 ) . '%' );
$sql2 = $wpdb->prepare( 'SELECT count( distinct( ip ) ) as cantidad FROM ' . $wpdb->prefix . 'visitas WHERE fecha LIKE %s', '%' . $wpdb->esc_like( $fecha3 ) . '%' );
$sql3 = $wpdb->prepare( 'SELECT count( * ) as impresiones FROM ' . $wpdb->prefix . 'visitas WHERE fecha LIKE %s', '%' . $wpdb->esc_like( $fecha3 ) . '%' );
$sql4 = $wpdb->prepare( 'SELECT detalle, count( * ) as cantidad FROM ' . $wpdb->prefix . 'visitas WHERE fecha LIKE %s group by detalle order by cantidad desc', '%' . $wpdb->esc_like( $fecha3 ) . '%' );
$sql5 = $wpdb->prepare( 'SELECT count( * ) as cantidad,pais FROM ' . $wpdb->prefix . 'visitas WHERE substr( fecha, 6, 2 ) = %d group by pais order by cantidad desc', $mes );

$resulta2 = $wpdb->get_results( $sql2 );
foreach ( $resulta2 as $row2 ) {
	$cantidad = $row2->cantidad;
}

$resulta3 = $wpdb->get_results( $sql3 );
foreach ( $resulta3 as $row3 ) {
	$impresiones = $row3->impresiones;
}
?>
<table width='100%' border='0' valign='top'>
<tr>
<td valign='top'>
	<table width='100%' border='0'>
	<tr>
	<td colspan='6' valign='top' class='punteado' bgcolor="#A9E2F3" align='center'>
	<h2><?php esc_html_e( 'Impresiones:', 'visitas' ); ?> (<?php echo $impresiones; ?>)</h2></td>
	</tr><tr><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'IP', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Página visitada', 'visitas' ); ?></b></td>
	<td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Hora', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'País', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Referencia', 'visitas' ); ?></b></td>

	<?php
	$resulta0 = $wpdb->get_results( $sql );
	foreach ( $resulta0 as $row ) {
		?>
		</tr><tr><td class='punteado'><?php echo $row->ip; ?></td>
		<td class='punteado'><?php echo esc_attr( $row->detalle ); ?></td>
		<td class='punteado'><?php echo date( 'H:i:s', strtotime( $row->fecha ) ); ?></td>
		<td class='punteado'><?php echo $row->pais; ?></td>
		<td class='punteado'><?php echo $row->de_donde; ?></td>
		<?php
	}
	?>
	</tr>
	</table>
</td></tr></table>
<div id='anchotabla'>
	<div id='izq'>
		<table width='100%' border='0'>
		<tr>
		<td class='punteado' colspan='4' bgcolor="#A9E2F3" align='center'><h2><?php esc_html_e( 'IP únicas', 'visitas' ); ?> (<?php echo $cantidad; ?>)</h2></td></tr>
		<tr><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'IP', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Cantidad', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'País', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Referencia', 'visitas' ); ?></b></td>
		<?php
		$resulta1 = $wpdb->get_results( $sql1 );
		foreach ( $resulta1 as $row1 ) {
			?>
			<tr><td class='punteado'><?php echo $row1->ip; ?></td>
			<td class='punteado'><?php echo (int) $row1->cantidad; ?></td>
			<td class='punteado'><?php echo $row1->pais; ?></td>
			<td class='punteado'><?php echo $row1->de_donde; ?></td>
			<?php
		}
		?>
		</table>
	</div>
	<div id='der'>
		<table width='100%' border='0' align='right'><tr>
		<td class='punteado' colspan='2' bgcolor="#A9E2F3" align='center'><h2><?php esc_html_e( 'Páginas MÁS Visitadas', 'visitas' ); ?></h2></td></tr>
		<tr><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Detalle', 'visitas' ); ?></b></td><td class='punteado' bgcolor="#D8D8D8"><b><?php esc_html_e( 'Cantidad de Veces', 'visitas' ); ?></b></td>
		<?php
		$resulta4 = $wpdb->get_results( $sql4 );
		foreach ( $resulta4 as $row4 ) {
			?>
			</tr><tr><td class='punteado'><?php echo esc_attr( $row4->detalle ); ?></td>
			<td class='punteado'><?php echo (int) $row4->cantidad; ?></td>
			<?php
		}
		?>
		</tr></table>
	</div>
	<div style="clear:left"></div>
	<div class="table-responsive">
		<table class="table" width="50%" border='0'><tr>
		<thead>
			<tr>
			<th colspan='2' class='punteado' bgcolor="#A9E2F3" align='center'><h2><?php esc_html_e( 'Total Visitas MES', 'visitas' ); ?></h2></th>
			</tr>
			<tr>
			<th class='punteado' bgcolor="#D8D8D8"><?php esc_html_e( 'País', 'visitas' ); ?></th>
			<th class='punteado' bgcolor="#D8D8D8"><?php esc_html_e( 'Cantidad', 'visitas' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$total   = 0;
		$result2 = $wpdb->get_results( $sql5 );
		foreach ( $result2 as $row2 ) {
			$total = $total + $row2->cantidad;
			?>
			<tr><td class='punteado'><?php echo $row2->pais; ?></td>
			<td class='punteado'><?php echo (int) $row2->cantidad; ?></td></tr>
			<?php
		}
		?>
		<tr><td class="punteado" align='right'><b><?php esc_html_e( 'Total Visitas Mes:', 'visitas' ); ?></b></td>
		<td class="punteado"><b><?php echo (int) $total; ?></b></td></tr>
		</tbody>
		</table>
	</div>
</div>
<b><?php echo filter_input( INPUT_SERVER, 'HTTP_HOST' ); ?><br>
<?php esc_html_e( 'Fecha del Informe:', 'visitas' ); ?> <?php echo $fecha; ?> <?php echo $hora; ?></b>
<br>
