<?php

if (!function_exists('getTemplateDisplayName')) {
    function getTemplateDisplayName($type) {
        $map = [
            'nathan' => 'Nathan Template',
            'esey' => 'Esey Template',
            'mirian' => 'Mirian Template',
            // Agrega más si tienes otros templates
        ];
        return $map[$type] ?? ucfirst($type); // Fallback a mayúscula inicial
    }
}

// Si necesitas otras funciones del original (ej. getTemplateName), agrégalas aquí
if (!function_exists('getTemplateName')) {
    function getTemplateName($numericType) {
        $map = [
            1 => 'nathan',
            2 => 'esey',
            3 => 'mirian',
            // Agrega más mappings numéricos
        ];
        return $map[$numericType] ?? 'nathan'; // Fallback
    }
}
