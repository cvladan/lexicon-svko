(() => {
    'use strict';

    const cptname = arrTooltips.post_type_name;
    
    // Find all relevant links
    const links = document.querySelectorAll(`a.${cptname}, ul.taxonomy-list li.cat-item > a`);

    // Add tooltip attributes to each link
    links.forEach(link => {
        // Get the text content of the link for the tooltip
        const tooltipText = link.textContent.trim();
        
        // Add data attributes for the tooltip
        link.setAttribute('data-tooltip', tooltipText);
        
        // Determine tooltip position based on available space
        link.addEventListener('mouseenter', () => {
            const rect = link.getBoundingClientRect();
            const spaceAbove = rect.top;
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceLeft = rect.left;
            const spaceRight = window.innerWidth - rect.right;
            
            // Find the side with most space
            const spaces = [
                { position: 'top', space: spaceAbove },
                { position: 'bottom', space: spaceBelow },
                { position: 'left', space: spaceLeft },
                { position: 'right', space: spaceRight }
            ];
            
            // Sort spaces from largest to smallest
            spaces.sort((a, b) => b.space - a.space);
            
            // Use the position with most space
            link.setAttribute('data-position', spaces[0].position);
        });
    });
})();
