<?php
    namespace App\Components;
    class TextEditor
    {
        public static function mbUcfirst(string $text): string
        {
            return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
        }
    }