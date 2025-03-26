document.addEventListener('DOMContentLoaded', function() {
    const quoteElement = document.getElementById('quote');

    if (!quoteElement) {
        console.error('Quote element not found');
        return;
    }

    const quotes = [
        "Manchester is red.",
        "Write it on your heart that every day is the best day in the year.",
        "The only way to do great work is to love what you do.",
        "Your life is your story. Write well. Edit often.",
        "Today is your opportunity to build the tomorrow you want.",
        "Every moment is a fresh beginning."
    ];

    let currentQuoteIndex = 0;

    function showNextQuote() {
        quoteElement.style.opacity = '0';
        
        setTimeout(() => {
            quoteElement.textContent = quotes[currentQuoteIndex];
            quoteElement.style.opacity = '1';
            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
        }, 500);
    }
    
    showNextQuote();
    setInterval(showNextQuote, 4000);
});