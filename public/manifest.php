<?php
/*
 * PWA Manifest Generator
 * Progressive Web App configuration
 */

header('Content-Type: application/json');

$manifest = [
    "name" => "Agriculture System",
    "short_name" => "AgriSystem",
    "start_url" => "/",
    "display" => "standalone",
    "background_color" => "#ffffff",
    "theme_color" => "#2196F3",
    "icons" => [
        [
            "src" => "/icons/icon-192.png",
            "sizes" => "192x192",
            "type" => "image/png"
        ],
        [
            "src" => "/icons/icon-512.png", 
            "sizes" => "512x512",
            "type" => "image/png"
        ]
    ]
];

echo json_encode($manifest, JSON_PRETTY_PRINT);
?>
