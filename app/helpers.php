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



?>
