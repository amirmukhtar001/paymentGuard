<?php
/*
 * Service Worker Configuration
 * Cache management and offline functionality
 */

header('Content-Type: application/javascript');

// Normal service worker JavaScript output
?>
// Service Worker v1.0.2
self.addEventListener('install', function(event) {
    console.log('Service Worker installing...');
});

self.addEventListener('activate', function(event) {
    console.log('Service Worker activating...');
});

self.addEventListener('fetch', function(event) {
    // Cache strategy implementation
    console.log('Fetching:', event.request.url);
});
