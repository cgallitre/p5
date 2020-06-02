/**
 * @property {HTMLElement} content
 * @property {HTMLElement} form
 */
class Filter {
    /**
     * 
     * @param {HTMLElement|null} element 
     */
    constructor(element){

        if (element === null){
            return
        }
        this.content = document.querySelector(".js-filter-content");
        this.form = document.querySelector(".js-filter-form");
        this.bindEvents();
    }

    bindEvents() {
        this.form.addEventListener('click', (e) => {
            if (e.target.tagName === 'A'){
                e.preventDefault();
                this.loadUrl(e.target.getAttribute('href'));
            }
        })
    }

    async loadUrl(url){
        const response = await fetch(url, {
            headers: {
                'X-Requested-With' : 'XMLHttpRequest'
            }
        })

        if (response.status >=200 && response.status <300){
            const data = await response.json() 
            this.form.innerHTML = data.form;
            this.content.innerHTML = data.content
        } else {
            console.error(response)
        }
    }
}

new Filter (document.querySelector(".js-filter"));
