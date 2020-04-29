const handleIntersect = function(entries, observer) {
    entries.forEach(entry => {
        if (entry.intersectionRatio > 0) {
            entry.target.classList.add('reveal-visible')
            observer.unobserve(entry.target)
        }
    });
}


const observer = new IntersectionObserver(handleIntersect)

document.querySelectorAll('[class*="reveal-"]').forEach( r => {
    observer.observe(r)
})