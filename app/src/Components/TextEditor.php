<?php
    namespace App\Components;
    /**
     * Work with text including many-bytes strings.
     * @method public static mbUcfirst(string $text): string.
     */
    class TextEditor
    {
        /**
         * Uppercase first symbol of recieved string.
         * @param string $text - text to be changed.
         * @return string.
         */
        public static function mbUcfirst(string $text): string
        {
            return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
        }
    }