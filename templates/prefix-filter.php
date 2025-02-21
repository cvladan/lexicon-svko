<?php
/*
- Use the $filter var to access all available filters
- Use $wrapper_class to add additional wrapper classes
*/

use SVKO\Lexicon\PostType;

$wrapperClass = $wrapper_class ?? '';
echo '<div class="' . PostType::getPostTypeName() . '-prefix-filters default-prefix-filters' . $wrapperClass . '">';

foreach ($filter as $level => $filter_line) {
    echo '<ul class="filter-level level-' . ($level + 1) . '">';
    
    foreach ($filter_line as $element) {
        $element->caption = htmlspecialchars($element->prefix, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
        $filterClasses = 'filter' . ($element->active ? ' current' : '') . ($element->disabled ? ' disabled' : '');
        
        echo "<li class='{$filterClasses}'>";
        echo $element->disabled ? "<span class='filter-link'>{$element->caption}</span>" : "<a href='{$element->link}' class='filter-link'>{$element->caption}</a>";
        echo '</li>';
    }
    
    echo '</ul>';
}

echo '</div>';
