// Article search function from about page to events


// Handles live search function with keyword input in search bar
document.addEventListener('DOMContentLoaded', function () { // Ensures script only runs after DOM loaded

    // Get references to key DOM elements
    const searchInput = document.getElementById('live-search'); // Text input for the search
    const searchButton = document.getElementById('search-button');  // Search button
    const articleContent = document.getElementById('article-content'); // Entire article to execute the search 

    // Initialize variables for storing (matches, current index)
    let matches = []; // Track matches
    let currentHighlightIndex = -1; // Track current position, helps in cycling through results

    // Highlight all matches of the search term
    function highlightText(searchTerm) {
        
        // Clear previous highlights by replacing the <span> with plain text
        const highlightedSpans = articleContent.querySelectorAll('.search-highlight');
        highlightedSpans.forEach(span => {
            const parent = span.parentNode;
            parent.replaceChild(document.createTextNode(span.textContent), span); // Replace span with plain text
            parent.normalize(); // Merge adjacent text nodes if any, cleans DOM
        });

        // Reset previous matches and current index to start fresh
        matches = [];
        currentHighlightIndex = -1;

        // Exit early if search term is empty or only whitespace
        if (!searchTerm.trim()) return;

        // Collect all text nodes inside articleContent using TreeWalker (DOM API)
        const textNodes = [];
        const walker = document.createTreeWalker(articleContent, NodeFilter.SHOW_TEXT, null, false); // Only return text node "xxx"
        let node;
        while (node = walker.nextNode()) {
            if (node.nodeValue.trim()) {
                textNodes.push(node); // Only include non-empty text nodes
            }
        }

        // Create a regular expression for search term (global, case-insensitive)
        const regex = new RegExp(searchTerm, 'gi');

        // Loop through all text nodes and replace matches with highlight spans
        textNodes.forEach(textNode => {
            const content = textNode.nodeValue;
            const parent = textNode.parentNode;

            // Check if the search term exists inside the current text node
            if (regex.test(content)) {
                // Replace matching words with a highlight span wrapper
                const newContent = content.replace(regex, match => {
                    return `<span class="search-highlight">${match}</span>`;
                });

                // Convert the new HTML string into actual DOM nodes
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = newContent;

                // First, insert the new highlighted elements before the old text node
                while (tempDiv.firstChild) {
                    parent.insertBefore(tempDiv.firstChild, textNode);
                }

                // Then, remove the old text node after replacement
                parent.removeChild(textNode);

                // Finds all new <span> just added that contain matches
                const newHighlights = parent.querySelectorAll('.search-highlight');

                // Add them to the matches array
                newHighlights.forEach(hl => matches.push(hl));
            }
        });
    }

    // To scroll to the next match in the matches list
    function scrollToNextMatch() {
        // If there are no matches, do nothing
        if (matches.length === 0) return;

        // Remove the "active" class from all current highlights, for styling reset
        matches.forEach(m => m.classList.remove('active'));

        // Move to the next match in the list (loop back to 0 after last)
        currentHighlightIndex = (currentHighlightIndex + 1) % matches.length;

        // Add the "active" class to the current match
        const nextMatch = matches[currentHighlightIndex];
        nextMatch.classList.add('active');

        // Show the current match in vertical center 
        nextMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    let lastSearchTerm = ''; // Keep track of the previous term
    
    // Combines highlight and scroll into one action    
    function handleSearch() {
        const searchTerm = searchInput.value.trim();

        // If it's a new search term, reset and highlight
        if (searchTerm !== lastSearchTerm) {
            highlightText(searchTerm); // Highlight all matches
            lastSearchTerm = searchTerm;
        }

        // Always scroll to the next match
        scrollToNextMatch();
    }

    // When user clicks the search button, run search logic
    searchButton.addEventListener('click', handleSearch);

    // When user clicks 'Enter',
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission or page reload
            handleSearch();     // Run search logic
        }
    });

    // Live highlighting while typing 
    searchInput.addEventListener('input', function () {
        highlightText(this.value); // Just highlight, no scroll
    });
});