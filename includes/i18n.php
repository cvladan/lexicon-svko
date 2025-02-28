<?php

namespace SVKO\Lexicon;

abstract class I18n
{
    const
        textdomain = 'lexicon-svko';

    private static
        $loaded = false;

    public static function loadTextDomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), static::textdomain);
        $language_folder = Core::$plugin_folder . '/languages';

        load_plugin_textdomain(static::textdomain);
        load_textdomain(static::textdomain, "{$language_folder}/{$locale}.mo");

        # Fallback for german
        if (in_array($locale, ['de_AT_formal', 'de_CH']))
            load_textdomain(static::textdomain, "{$language_folder}/de_DE_formal.mo");
        elseif (in_array($locale, ['de_AT', 'de_CH_informal']))
            load_textdomain(static::textdomain, "{$language_folder}/de_DE.mo");

        static::$loaded = true;
    }

    public static function translate(string $text, string $context = ''): string
    {
        if (apply_filters('lexicon_translate', true, $text, $context)) {
            # Load text domain
            if (!static::$loaded) static::loadTextDomain();

            # Translate the string $text with context $context
            if (empty($context))
                return translate($text, static::textdomain);
            else
                return translate_with_gettext_context($text, $context, static::textdomain);
        } else {
            return $text;
        }
    }

    public static function t(string $text, string $context = ''): string
    {
        return static::translate($text, $context);
    }

    public static function __(string $text): string
    {
        return static::translate($text);
    }

    public static function _e(string $text): void
    {
        echo static::translate($text);
    }

    public static function _x(string $text, string $context): string
    {
        return static::translate($text, $context);
    }

    public static function _ex(string $text, string $context): void
    {
        echo static::translate($text, $context);
    }
}
