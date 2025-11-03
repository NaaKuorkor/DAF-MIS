document.addEventListener('DOMContentLoaded', () => {

    const first = document.getElementById('first');
    const second = document.getElementById('second');
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');

    nextBtn.addEventListener('click', () => {
        first.classList.add('opacity-0', 'translate-x-[-10px]');
        setTimeout(() => {
            first.classList.add("hidden");
            second.classList.remove("hidden");

            setTimeout(() => {
                second.classList.remove('opacity-0', 'translate-x-10');
            },10);
        }, 300);

    } )

    backBtn.addEventListener('click', () => {
        second.classList.add('opacity-0', 'translate-x-10');
        setTimeout(()=>{
            second.classList.add('hidden');
            first.classList.remove('hidden');

            setTimeout(()=>{
                first.classList.remove('opacity-0', 'translate-x-10');
            }, 10);
        }, 300);

    })

})
