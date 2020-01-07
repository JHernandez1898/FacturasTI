<?php
/**
 * Clase que implementa un coversor de números
 * a letras.
 *
 * Soporte para PHP >= 5.4
 * Para soportar PHP 5.3, declare los arreglos
 * con la función array.
 *
 * @author AxiaCore S.A.S
 *
 */

class NumeroALetras
{
    private static $UNIDADES = [
        '',
        'ONE ',
        'TWO ',
        'THREE ',
        'FOUR ',
        'FIVE ',
        'SIX ',
        'SEVEN ',
        'EIGHT',
        'NINE ',
        'TEN ',
        'ELEVEN ',
        'TWELVE ',
        'THIRTHEEN ',
        'FOURTHTEEN ',
        'FIFTEEN ',
        'SIXTEEN ',
        'SEVENTEEN ',
        'EIGHTTEEN ',
        'NINETEEN ',
        'TWENTY '
    ];

    private static $DECENAS = [
        'TWENTY ',
        'THIRTY ',
        'FORTY ',
        'FIFTY ',
        'SIXTY ',
        'SEVENTY ',
        'EIGHTY ',
        'NINETY ',
        'ONE HUNDREND '
    ];

    private static $CENTENAS = [
        'ONE HUNDRED ',
        'TWO HUNDRED ',
        'THREE HUNDRED ',
        'FOUR HUNDRED ',
        'FIVE HUNDRED ',
        'SIX HUNDRED',
        'SEVEN HUNDRED ',
        'EIGHT HUNDRED ',
        'NINE HUNDRED '
    ];

    public static function convertir($number, $moneda = '', $centimos = '', $forzarCentimos = FALSE)
    {
        $converted = '';
        $decimales = '';

        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }

        $div_decimales = explode('.',$number);

        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        else if (count($div_decimales) == 1 && $forzarCentimos){
            $decimales = 'ZERO ';
        }

        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'ONE MILLION';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONS ', self::convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'THOUNSAND ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sTHOUSAND ', self::convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'ONE ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }

        if(empty($decimales)){
            $valor_convertido = $converted . strtoupper($moneda);
        } else {
            $valor_convertido = $converted . strtoupper($moneda) . ' AND ' . $decimales . ' ' . strtoupper($centimos);
        }

        return $valor_convertido;
    }

    private static function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = "ONE HUNDRED ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%s %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}
