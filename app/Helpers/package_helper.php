<?php

/**
 * Format nominal Rupiah paket untuk tampilan: 31200000 -> "31,2 JT"
 *
 * @param int|float|string $price Nominal Rupiah (penuh) atau nilai dalam juta (legacy)
 * @return string
 */
function format_price_display($price)
{
    $price = (float) $price;
    if ($price <= 0) {
        return '0 JT';
    }
    if ($price >= 1_000_000) {
        $juta = $price / 1_000_000;
    } else {
        $juta = $price;
    }
    return number_format($juta, 1, ',', '') . ' JT';
}
