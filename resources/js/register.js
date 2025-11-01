const first = document.getElementById('first');
const second = document.getElementById('second');
const nextBtn = document.getElementById('nextBtn');
const backBtn = document.getElementById('backBtn');

nextBtn.addEventListener('click', () => {
    first.classList.add("hidden");
    second.classList.remove("hidden");
} )

backBtn.addEventListener('click', () => {
    first.classList.remove("hidden");
    second.classList.add("hidden")
})
