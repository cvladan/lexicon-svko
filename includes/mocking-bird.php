<?php

namespace SVKO\Lexicon;

abstract class MockingBird
{
    public static
        $banner_id;

    public static function init(): void
    {
    }

    public static function printProNotice(string $message_id = 'option'): void
    {
        echo " 🔥 Go Fuck Yourself 🔥";
    }

}

MockingBird::init();
