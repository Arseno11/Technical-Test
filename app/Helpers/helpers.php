<?php

use Carbon\Carbon;

if (!function_exists('formatCurrency')) {
  function formatCurrency($amount)
  {
    return number_format($amount, 0, ',', '.');
  }
}

if (!function_exists('formatWaktu')) {
  function formatWaktu($dateTime)
  {
    return Carbon::parse($dateTime)->format('d M, Y');
  }
}

if (!function_exists('generateOrderCode')) {
  function generateOrderCode($prefix = 'INV')
  {
    $dateCode = $prefix . '/' . date('Ymd') . '/';
    return $dateCode;
  }
}
