<?php
/**
 * Custom Order Status Badge
 *
 * @var string $status
 */

$status = $status ?? 'Masuk';
$tones = [
    'Masuk' => 'blue',
    'Diproses' => 'yellow',
    'Dalam Pembuatan' => 'orange',
    'Selesai' => 'green',
    'Diambil' => 'grey',
];
?>
<?= view('components/ui/badge', [
    'tone' => $tones[$status] ?? 'grey',
    'text' => $status,
    'dot' => true,
]) ?>
