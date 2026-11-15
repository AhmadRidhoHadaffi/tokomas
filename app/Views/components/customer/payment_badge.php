<?php
/**
 * Payment Method Badge
 *
 * @var string $method Tunai|Transfer|QRIS
 */

$method = $method ?? 'Tunai';
$tones = [
    'Tunai' => 'grey',
    'Transfer' => 'blue',
    'QRIS' => 'gold',
];
?>
<?= badge([
    'tone' => $tones[$method] ?? 'grey',
    'text' => $method,
]) ?>
