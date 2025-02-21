(function ($) {
    'use strict';

    const cptname = arrTooltips.post_type_name;

    const tooltipster_args = {
        distance: 5, // distance between the origin and the tooltip, in pixels
        maxWidth: 480, // maximum width for the tooltip
        theme: cptname + '-tooltip', // this will be added as class to the tooltip wrapper
    };

    arrTooltips.$links = $(`a.${cptname}, .widget_${cptname}_taxonomies ul.taxonomy-list li.cat-item > a`);

    // initialize the tooltips
    arrTooltips.links = arrTooltips.$links.tooltipster(tooltipster_args);

}(jQuery));
