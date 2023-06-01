<?php
/*
 * Plugin Name: CF7 PESEL Validation 
 * Plugin URI: //github.com/kglaz
 * Description: General Electronic Population Registration System (Polish number PESEL) Validation to CF7
 * Author: Krzysztof Głaz
 * Author URI: //judoinfo.pl
 * Version: 0.5
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) exit;

add_filter( 'wpcf7_validate_text*', 'custom_text_pesel_validation_filter', 20, 2 );

function custom_text_pesel_validation_filter( $result, $tag ) {

	if ( 'pesel' == $tag->name ) {
		$pesel = isset( $_POST['pesel'] ) ? trim( $_POST['pesel'] ) : '';

		/* Sprawdzenie czy liczba znaków się zgadza */
		if (!preg_match('/^[0-9]{11}$/',$pesel)) {
		    $result->invalidate( $tag, "Błędna liczba znaków" );
		}

		/* Sprawdzenie sumy kontrolnej */
		$arrayWagi = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3);
		$SumujWagi = 0;
		for ($i = 0; $i < 10; $i++) {
			$SumujWagi += $arrayWagi[$i] * $pesel[$i]; // pomnożenie każdego znaku przez wagę i zsumowanie
		}
		
		$SumaKontrolna = 10 - $SumujWagi % 10;
		
		$SprSumaKontrolna = ($SumaKontrolna == 10)?0:$SumaKontrolna;
		
		/* Sprawdzenie czy suma kontrolna znajduje się w zestawie */
		if ($SprSumaKontrolna != $pesel[10]) {
	         $result->invalidate( $tag, "Błędny numer PESEL" );
		}

    }

	        return $result;
}

?>