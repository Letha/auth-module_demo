<?php
    /**
     * routes to controllers with their actions
     */
    return array(
        // First key means regular expression for client's query string.
        // First value:
        // - first part means method actionIndex
        // - second part means class IndexController of that method.
        // And so on for other elements.
        // Parts of value string after second slash means query parameters.
        '^$' => 'index/index'
    );