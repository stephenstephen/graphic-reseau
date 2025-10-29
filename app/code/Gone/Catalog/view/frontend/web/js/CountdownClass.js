class CountdownClass {

    constructor(
        endDate,
        mainDiv = "countdown",
        watchDivs = {
            'days':'#count-days',
            'hours':'#count-hours',
            'minutes':'#count-minutes',
            'seconds':'#count-seconds'
        }
    ) {
        this.endDate = new Date(endDate);
        //divs to displays clock elements
        this.mainDiv = document.querySelector(`#${mainDiv}`)
        this.watchDivs = watchDivs;
    }

    displayCountDown = () => {
        this.mainDiv.style.display = 'flex'
    }

    hideCountDown = () => {
        this.mainDiv.style.display = 'none'
    }

    getTimeRemaining = () => {
        const total = Date.parse (this.endDate) - Date.parse (new Date ());
        const seconds = Math.floor ((total / 1000)% 60);
        const minutes = Math.floor ((total / 1000/60)% 60);
        const hours = Math.floor ((total / (1000 * 60 * 60))% 24);
        const days = Math.floor (total / (1000 * 60 * 60 * 24));

        return {
            total,
            days,
            hours,
            minutes,
            seconds
        };
    }

    initClock = ()=> {

        const days = document.querySelector(this.watchDivs.days);
        const hours = document.querySelector(this.watchDivs.hours);
        const minutes = document.querySelector(this.watchDivs.minutes);
        const seconds= document.querySelector(this.watchDivs.seconds);

        let timeinterval = setInterval (() => {
            let t = this.getTimeRemaining (this.endDate);
            days.innerHTML = t.days.toString().replace(/^(\d)$/,'0$1')
            hours.innerHTML = t.hours.toString().replace(/^(\d)$/,'0$1')
            minutes.innerHTML = t.minutes.toString().replace(/^(\d)$/,'0$1')
            seconds.innerHTML = t.seconds.toString().replace(/^(\d)$/,'0$1')

            this.displayCountDown();
            if (t.total <= 0) {
                this.hideCountDown();
                clearInterval (timeinterval);
            }
        }, 1000);
    }
}
