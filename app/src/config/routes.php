<?php
    /**
     * routes to controllers with their actions
     */
    return array(
        /**
        * First key means regular expression for client's query string.
        * First value:
        * - first part means class IndexController;
        * - second part means method actionIndex of that class.
        * And so on for other elements.
        * Parts of value string after second slash means query parameters.
        */
        '^$' => 'index/index',
        '^ajax/login$' => 'enter/login',
        '^ajax/register$' => 'enter/register',
        '^ajax/exit$' => 'enter/exit',
        '^ajax/language$' => 'configuration/language',
        '^ajax/image/personal-photo$' => 'loadFiles/uploadPersonalPhoto'
    );