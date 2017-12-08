#!/usr/bin/env php
<?php

$columns = exec('tput cols');

$input = preg_split('//u', stream_get_contents(STDIN), -1, PREG_SPLIT_NO_EMPTY);
$inputLength = count($input);

$chars = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`ab';
$chars .= 'cdefghijklmnopqrstuvwxyz{|}~ÇüéâäàåçêëèïîìÄÅÉæÆôöòûùÿÖÜ¢£¥₧ƒáíóúñÑª';
$chars .= 'º¿⌐¬½¼¡«»░▒▓│┤╡╢╖╕╣║╗╝╜╛┐└┴┬├─┼╞╟╚╔╩╦╠═╬╧╨╤╥╙╘╒╓╫╪┘┌█▄▌▐▀αßΓπΣσµτΦΘ';
$chars .= 'Ωδ∞φε∩≡±≥≤⌠⌡÷≈°∙·√ⁿ²■';

$charset = preg_split('//u', $chars, -1, PREG_SPLIT_NO_EMPTY);
$charsetLength = count($charset);

$output = [];
$unsolved = [];
$lineLength = 0;
$newlineCount = 0;

echo "\033[2;49;39m";

// parse input and print scrambled text with delay
for ($i = 0; $i < $inputLength; $i++) {
    if ($lineLength >= $columns) {
        $newlineCount++;
        $lineLength = 0;
    }

    if ($input[$i] === "\n") {
        $output[] = "\n";
        $newlineCount++;
        $lineLength = 0;
    } else if ($input[$i] === ' ') {
        $output[] = ' ';
    } else {
        $output[] = $charset[rand(0, $charsetLength - 1)];
        $unsolved[$i] = $i;
    }

    echo $output[$i];
    usleep(2400);

    $lineLength++;
}

sleep(1);

// do n random cycles before starting to reveal correct chars
for ($i = 0; $i < 120; $i++) {
    echo "\033[{$newlineCount}A\r";

    for ($j = 0; $j < $inputLength; $j++) {
        echo $input[$j] !== ' ' && $input[$j] !== "\n"
            ? $charset[rand(0, $charsetLength - 1)]
            : $output[$j];
    }

    usleep(16000);
}

// "decrypt" input
$solved = 0;
while (!empty($unsolved)) {
    echo "\033[{$newlineCount}A\r";

    $forceDecrypt = rand(2, 8);
    foreach ($unsolved as $index => $i) {
        if ($forceDecrypt > 0) {
            $output[$i] = $input[$i];
            $forceDecrypt--;
        } else if (rand(0, 100) <= rand(6, (int) sqrt($solved))) {
            $output[$i] = $charset[rand(0, $charsetLength - 1)];
        }

        if ($output[$i] === $input[$i]) {
            $solved++;
            unset($unsolved[$index]);
        }
    }

    for ($i = 0; $i < $inputLength; $i++) {
        $char = $output[$i];

        echo $char === $input[$i]
            ? "\033[0;34m$char\033[0m"
            : "\033[2;49;39m$char\033[0m";
    }

    shuffle($unsolved);

    usleep(36000);
}
