<?php

namespace SVKO\Lexicon;

abstract class Template
{
    public static function init(): void
    {
        add_filter('search_template', array(static::class, 'changeSearchTemplate'));
    }

    public static function changeSearchTemplate(string $template): string
    {
        global $wp_query;

        if (Search::isThisSearched($wp_query) && $search_template = locate_template(sprintf('search-%s.php', PostType::getPostTypeName())))
            return $search_template;
        else
            return $template;
    }

    public static function load(string $template_name, array $vars = []): string
    {
        extract($vars);
        $template_path = locate_template($template_name);

        OB_Start();
        if (!empty($template_path))
            include $template_path;
        else
            include sprintf('%s/templates/%s', Core::$plugin_folder, $template_name);

        return (string) OB_Get_Clean();
    }
}

Template::init();
