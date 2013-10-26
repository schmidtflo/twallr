<?php
// Configuration

// Search term
// The term twitter is searched for
// See: https://dev.twitter.com/docs/api/1.1/get/search/tweets
$search = "q=hashtag";

// Displayed Search Term
// In the headnav displayed Search Term
$display = "#Hashtag";

// Reload time in seconds
// If you want to reload the page (recommended!)
// If you don't want to reload, set to 0.
$reload = 1200;  // <-- This are 20 minutes

// Refresh time in miliseconds
// Every X miliseconds the script tries to get new tweets.
// Because of twitters API-limits, don't set it lower than 5000ms.
$refresh = 10000;

// Tokens
// Get your tokens at dev.twitter.com
$key = "";
$key_secret = "";
$token = "";
$token_secret = "";



?>