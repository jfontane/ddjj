<?php

namespace Jubilaciones\DeclaracionesBundle\Twig;

use Twig\Extension\AbstractExtension\Twig_Extension;

class AppExtension extends Twig_Extension {

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('inicio_pdf', array($this, 'iniciopdfFunction')),
            new \Twig_SimpleFunction('final_pdf', array($this, 'finalpdfFunction')),
            new \Twig_SimpleFunction('obtener_fecha', array($this, 'obtenerFecha')),
        );
    }

    public function iniciopdfFunction() {
        ob_start();
        return;
    }

    public function finalpdfFunction($para_pdf) {
        $postsbis = ob_get_contents();
        $posts = $para_pdf . $postsbis;
        ob_end_clean();

        return $posts;
    }

    public function obtenerFecha() {
        setlocale(LC_ALL, "es_ES");
        $timestamp = time();
        $format = 'l j F Y H:i';

        $trans = array(
            'Monday' => 'Lunes,',
            'Tuesday' => 'Martes,',
            'Wednesday' => 'Miércoles,',
            'Thursday' => 'Jueves,',
            'Friday' => 'Viernes,',
            'Saturday' => 'Sábado,',
            'Sunday' => 'Domingo,',
            'January' => ' de Enero de ',
            'February' => ' de Febrero de ',
            'March' => ' de Marzo de ',
            'April' => ' de Abril de ',
            'May' => ' de Mayo de ',
            'June' => ' de Junio de ',
            'July' => ' de Julio de ',
            'August' => ' de Agosto de ',
            'September' => ' de Septiembre de ',
            'October' => ' de Octubre de ',
            'November' => ' de Noviembre de ',
            'December' => ' de Diciembre de ',
        );
        return strtr(date($format, $timestamp), $trans);
    }

    public function getName() {
        return 'app_extension';
    }

}
