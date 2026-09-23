<?php

if (!function_exists('calculateTotalGold')) {
    function calculateTotalGold($amounts) {
        $vori = $amounts['vori'] ?? 0;
        $ana = $amounts['ana'] ?? 0;
        $roti = $amounts['roti'] ?? 0;
        $point = $amounts['point'] ?? 0;

        $totalPoints = ($vori * 16 * 6 * 10) + ($ana * 6 * 10) + ($roti * 10) + $point;

        $totalRoti = intdiv($totalPoints, 10);
        $remainingPoints = $totalPoints % 10;

        $totalAna = intdiv($totalRoti, 6);
        $remainingRoti = $totalRoti % 6;

        $totalVori = intdiv($totalAna, 16);
        $remainingAna = $totalAna % 16;

        return [
            'vori' => $totalVori,
            'ana' => $remainingAna,
            'roti' => $remainingRoti,
            'point' => $remainingPoints
        ];
    }
}


if (!function_exists('subGold')) {
    function subGold($initial, $subtract) {
        // Convert both initial and subtract values to points
        $initialPoints = ($initial['vori'] * 16 * 6 * 10) + ($initial['ana'] * 6 * 10) + ($initial['roti'] * 10) + $initial['point'];
        $subtractPoints = ($subtract['vori'] * 16 * 6 * 10) + ($subtract['ana'] * 6 * 10) + ($subtract['roti'] * 10) + $subtract['point'];

        // Perform the subtraction
        $remainingPoints = $initialPoints - $subtractPoints;

        // Check for negative values
        if ($remainingPoints < 0) {
            return [
                'vori' => 0,
                'ana' => 0,
                'roti' => 0,
                'point' => 0
            ];
        }

        // Convert remaining points back to vori, ana, roti, point
        $remainingRoti = intdiv($remainingPoints, 10);
        $remainingPoints = $remainingPoints % 10;

        $remainingAna = intdiv($remainingRoti, 6);
        $remainingRoti = $remainingRoti % 6;

        $remainingVori = intdiv($remainingAna, 16);
        $remainingAna = $remainingAna % 16;

        return [
            'vori' => $remainingVori,
            'ana' => $remainingAna,
            'roti' => $remainingRoti,
            'point' => $remainingPoints
        ];
    }
}


if (!function_exists('addGold')) {
    function addGold($initial, $add) {
        // Convert both initial and add values to points
        $initialPoints = ($initial['vori'] * 16 * 6 * 10) + ($initial['ana'] * 6 * 10) + ($initial['roti'] * 10) + $initial['point'];
        $addPoints = ($add['vori'] * 16 * 6 * 10) + ($add['ana'] * 6 * 10) + ($add['roti'] * 10) + $add['point'];

        // Perform the addition
        $totalPoints = $initialPoints + $addPoints;

        // Convert total points back to vori, ana, roti, point
        $totalRoti = intdiv($totalPoints, 10);
        $remainingPoints = $totalPoints % 10;

        $totalAna = intdiv($totalRoti, 6);
        $remainingRoti = $totalRoti % 6;

        $totalVori = intdiv($totalAna, 16);
        $remainingAna = $totalAna % 16;

        return [
            'vori' => $totalVori,
            'ana' => $remainingAna,
            'roti' => $remainingRoti,
            'point' => $remainingPoints
        ];
    }
}

if (!function_exists('convertToGram')) {
    function convertToGram($gold) {
        // Conversion rates
        $voriToGram = 11.664;
        $anaToGram = $voriToGram / 16; // 1 ana is 1/16 of a vori
        $rotiToGram = $anaToGram / 6; // 1 roti is 1/6 of an ana
        $pointToGram = $rotiToGram / 10; // 1 point is 1/10 of a roti

        // Convert all units to grams
        $gram = ($gold['vori'] * $voriToGram) +
                ($gold['ana'] * $anaToGram) +
                ($gold['roti'] * $rotiToGram) +
                ($gold['point'] * $pointToGram);

        return $gram;
    }
}

if (!function_exists('convertToGold')) {
    function convertToGold($grams) {
        // Define conversion rates
        $voriToGram = 11.664;
        $anaToGram = $voriToGram / 16;
        $rotiToGram = $anaToGram / 6;
        $pointToGram = $rotiToGram / 10;
    
        // Calculate vori, ana, roti, and point
        $vori = intdiv($grams, $voriToGram);
        $remainingGrams = $grams % $voriToGram;
        
        $ana = intdiv($remainingGrams, $anaToGram);
        $remainingGrams %= $anaToGram;
        
        $roti = intdiv($remainingGrams, $rotiToGram);
        $remainingGrams %= $rotiToGram;
        
        $point = round($remainingGrams / $pointToGram);
    
        return [
            'vori' => $vori,
            'ana' => $ana,
            'roti' => $roti,
            'point' => $point
        ];
    }
}

if (!function_exists('numberToBanglaDigit')) {
    function numberToBanglaDigit($number) {
        $en = ['0','1','2','3','4','5','6','7','8','9','.'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯','.'];
        return str_replace($en, $bn, (string)$number);
    }
}

if (!function_exists('numberToEnglishWords')) {
    function numberToEnglishWords($num) {
        $num = (int)$num;
        if ($num === 0) return 'Zero';

        $units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                  'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $words = [];

        if ($num >= 10000000) { // Crore
            $words[] = numberToEnglishWords(intdiv($num, 10000000)) . ' Crore';
            $num %= 10000000;
        }
        if ($num >= 100000) { // Lakh
            $words[] = numberToEnglishWords(intdiv($num, 100000)) . ' Lakh';
            $num %= 100000;
        }
        if ($num >= 1000) { // Thousand
            $words[] = numberToEnglishWords(intdiv($num, 1000)) . ' Thousand';
            $num %= 1000;
        }
        if ($num >= 100) { // Hundred
            $words[] = numberToEnglishWords(intdiv($num, 100)) . ' Hundred';
            $num %= 100;
        }
        if ($num > 0) {
            if ($num < 20) {
                $words[] = $units[$num];
            } else {
                $w = $tens[intdiv($num, 10)];
                if ($num % 10 > 0) {
                    $w .= ' ' . $units[$num % 10];
                }
                $words[] = $w;
            }
        }
        return implode(' ', $words);
    }
}

if (!function_exists('numberToBanglaWords')) {
    function numberToBanglaWords($num) {
        $num = (int)$num;
        if ($num === 0) return 'শূন্য';

        $banglaWords = [
            0 => 'শূন্য', 1 => 'এক', 2 => 'দুই', 3 => 'তিন', 4 => 'চার', 5 => 'পাঁচ', 6 => 'ছয়', 7 => 'সাত', 8 => 'আট', 9 => 'নয়', 10 => 'দশ',
            11 => 'এগারো', 12 => 'বারো', 13 => 'তেরো', 14 => 'চৌদ্দ', 15 => 'পনেরো', 16 => 'ষোলো', 17 => 'সতেরো', 18 => 'আঠারো', 19 => 'উনিশ', 20 => 'বিশ',
            21 => 'একুশ', 22 => 'বাইশ', 23 => 'তেইশ', 24 => 'চব্বিশ', 25 => 'পঁচিশ', 26 => 'ছাব্বিশ', 27 => 'সাতাশ', 28 => 'আটাশ', 29 => 'উনত্রিশ', 30 => 'ত্রিশ',
            31 => 'একত্রিশ', 32 => 'বত্রিশ', 33 => 'তেত্রিশ', 34 => 'চৌত্রিশ', 35 => 'পঁয়ত্রিশ', 36 => 'ছত্রিশ', 37 => 'সাঁইত্রিশ', 38 => 'আটত্রিশ', 39 => 'উনচল্লিশ', 40 => 'চল্লিশ',
            41 => 'একচল্লিশ', 42 => 'বিয়াল্লিশ', 43 => 'তেতাল্লিশ', 44 => 'চুয়াল্লিশ', 45 => 'পঁয়তাল্লিশ', 46 => 'ছেচল্লিশ', 47 => 'সাতচল্লিশ', 48 => 'আটচল্লিশ', 49 => 'উনপঞ্চাশ', 50 => 'পঞ্চাশ',
            51 => 'একান্ন', 52 => 'বায়ান্ন', 53 => 'তিপ্পান্ন', 54 => 'চুয়ান্ন', 55 => 'পঞ্চান্ন', 56 => 'ছাপ্পান্ন', 57 => 'সাতান্ন', 58 => 'আটান্ন', 59 => 'উনষাট', 60 => 'ষাট',
            61 => 'একষট্টি', 62 => 'বাষট্টি', 63 => 'তেষট্টি', 64 => 'চৌষট্টি', 65 => 'পঁয়ষট্টি', 66 => 'ছেষট্টি', 67 => 'সাতষট্টি', 68 => 'আটষট্টি', 69 => 'উনসত্তর', 70 => 'সত্তর',
            71 => 'একাত্তর', 72 => 'বাহাত্তর', 73 => 'তিয়াত্তর', 74 => 'চৌহাত্তর', 75 => 'পঁচাত্তর', 76 => 'ছিয়াত্তর', 77 => 'সাতাত্তর', 78 => 'আটাত্তর', 79 => 'উনাশি', 80 => 'আশি',
            81 => 'একাশি', 82 => 'বিরাশি', 83 => 'তিরাশি', 84 => 'চুরাশি', 85 => 'পঁচাশি', 86 => 'ছিয়াশি', 87 => 'সাতাশি', 88 => 'অষ্টাদশ', 89 => 'ঊননব্বই', 90 => 'নব্বই',
            91 => 'একানব্বই', 92 => 'বিরানব্বই', 93 => 'তিরানব্বই', 94 => 'চুরানব্বই', 95 => 'পঁচানব্বই', 96 => 'ছিয়ানব্বই', 97 => 'সাতানব্বই', 98 => 'আটানব্বই', 99 => 'নিরানব্বই'
        ];

        $parts = [];

        if ($num >= 10000000) { // কোটি (Crore)
            $crore = intdiv($num, 10000000);
            $parts[] = numberToBanglaWords($crore) . ' কোটি';
            $num %= 10000000;
        }
        if ($num >= 100000) { // লাখ (Lakh)
            $lakh = intdiv($num, 100000);
            $parts[] = numberToBanglaWords($lakh) . ' লাখ';
            $num %= 100000;
        }
        if ($num >= 1000) { // হাজার (Thousand)
            $thousand = intdiv($num, 1000);
            $parts[] = numberToBanglaWords($thousand) . ' হাজার';
            $num %= 1000;
        }
        if ($num >= 100) { // শত (Hundred)
            $hundred = intdiv($num, 100);
            $parts[] = ($hundred === 1 ? 'একশত' : ($banglaWords[$hundred] ?? (string)$hundred) . ' শত');
            $num %= 100;
        }
        if ($num > 0) {
            $parts[] = $banglaWords[$num] ?? (string)$num;
        }

        return implode(' ', $parts);
    }
}

if (!function_exists('generateBarcodeSvg')) {
    function generateBarcodeSvg($code, $height = 28, $narrowWidth = 1.2, $wideWidth = 3.0) {
        $code39 = [
            '0' => '000110100', '1' => '100100001', '2' => '001100001', '3' => '101100000',
            '4' => '000110001', '5' => '100110000', '6' => '001110000', '7' => '000100101',
            '8' => '100100100', '9' => '001100100', 'A' => '100001001', 'B' => '001001001',
            'C' => '101001000', 'D' => '000011001', 'E' => '100011000', 'F' => '001011000',
            'G' => '000001101', 'H' => '100001100', 'I' => '001001100', 'J' => '000011100',
            'K' => '100000011', 'L' => '001000011', 'M' => '101000010', 'N' => '000010011',
            'O' => '100010010', 'P' => '001010010', 'Q' => '000000111', 'R' => '100000110',
            'S' => '001000110', 'T' => '000010110', 'U' => '110000001', 'V' => '011000001',
            'W' => '111000000', 'X' => '010010001', 'Y' => '110010000', 'Z' => '011010000',
            '-' => '010000101', '.' => '110000100', ' ' => '011000100', '$' => '010101000',
            '/' => '010100010', '+' => '010001010', '%' => '000101010', '*' => '010010100'
        ];

        $clean = strtoupper(preg_replace('/[^0-9A-Z\-\. \$\/\+\%]/', '', (string)$code));
        if (empty($clean)) {
            $clean = 'INVOICE';
        }
        $formatted = '*' . $clean . '*';
        $rects = [];
        $x = 0;

        for ($i = 0; $i < strlen($formatted); $i++) {
            $char = $formatted[$i];
            if (!isset($code39[$char])) {
                $char = '-';
            }
            $pattern = $code39[$char];
            for ($b = 0; $b < 9; $b++) {
                $isBar = ($b % 2 === 0);
                $isWide = ($pattern[$b] === '1');
                $w = $isWide ? $wideWidth : $narrowWidth;
                if ($isBar) {
                    $rects[] = "<rect x=\"{$x}\" y=\"0\" width=\"{$w}\" height=\"{$height}\" fill=\"#1a1a1a\" />";
                }
                $x += $w;
            }
            // Inter-character space
            $x += $narrowWidth;
        }

        return "<svg width=\"{$x}\" height=\"{$height}\" viewBox=\"0 0 {$x} {$height}\" xmlns=\"http://www.w3.org/2000/svg\" style=\"max-width:100%;height:{$height}px;display:block;\">" . implode('', $rects) . "</svg>";
    }
}

