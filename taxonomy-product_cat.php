<?php
/**
 * Fallback para categorías de producto del child theme.
 * Mantiene una sola implementación de catálogo para evitar divergencias.
 */
if (!defined('ABSPATH')) { exit; }
include get_stylesheet_directory() . '/archive-product.php';
