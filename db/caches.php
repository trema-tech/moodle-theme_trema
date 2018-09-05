<?php 

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

$definitions = array(
    'dashboardadmin' => array(
        'mode' => cache_store::MODE_APPLICATION,
        'ttl' => 3600, // One hour.
    )
);
