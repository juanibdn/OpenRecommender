<?php

$WIKI_SLURP_CONFIG = array(
    'SECRET' => '', // YOU NEED TO CHANGE THIS TO SOMETHING SECRET
    'SEARCH_API_KEY' => '', // YOU NEED TO CHANGE THIS - GET ONE FROM http://developer.yahoo.com/search/boss/
    
    'WIKI_DOMAIN' => 'en.wikipedia.org',
    'WIKI_API' => '/w/api.php',
    'WIKI_BASE_DIR' => '/wiki/',
    'WIKI_CACHE_TIME' => 60 * 60 * 24, // ONE DAY
    'WIKI_HTML_CACHE_TIME' => 60 * 60 * 24 * 365, // ONE YEAR
    'SEARCH_DOMAIN' => 'boss.yahooapis.com',
    'SEARCH_API' => '/ysearch/web/v1/',
    'SEARCH_CACHE_TIME' => 60 * 60 * 24 * 7, // SEVEN DAYS
);