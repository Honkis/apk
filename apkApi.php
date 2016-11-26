<?php
namespace apkCalculator;
include_once('apkService.php');

$apk = new apkService;

// Create the options array to pass to backend
$options = [];


// Sanitize input
if (isset($_POST['category'])) {
  $options['category'] = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
}

if (isset($_POST['method'])) {
  $options['method'] = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
}

if (isset($_POST['offset'])) {
  $options['offset'] = (int)filter_var($_POST['offset'], FILTER_SANITIZE_NUMBER_INT);
}


// If category is övrigt then set a different method
if (isset($options['category']) && $options['category'] == 'övrigt') {
    $options['method'] = 'get_other_articles';
}


// Make API call depending on method chosen
if ($options['method'] == 'get_articles') {
  $data = $apk->get_articles($options);
}

if ($options['method'] == 'get_more_articles') {
  $data = $apk->get_more_articles($options);
}

if ($options['method'] == 'get_other_articles') {
  $data = $apk->get_other_articles($options);
}

if ($options['method'] == 'get_worst_apk_articles') {
  $data = $apk->get_worst_apk_articles();
}


// Encode the data and check for errors
$data = json_encode($data);
if (json_last_error() != JSON_ERROR_NONE) {
  echo 'Error retrieving data from database.';
  exit();
}

print_r($data);