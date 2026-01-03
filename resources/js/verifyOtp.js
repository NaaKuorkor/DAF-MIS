    const inputs = document.querySelectorAll('.otp-input');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const timerDisplay = document.getElementById('timer');
    const otpValue = document.getElementById('otp-value');
    let timeLeft = 59;
    let timerId = null;

    // Auto-focus logic
    inputs.forEach((input, index) => {
        // Handle number input
        input.addEventListener('input', (e) => {
            // Ensure only numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');

            if (e.target.value.length > 1) {
                e.target.value = e.target.value.slice(0, 1);
            }

            // Update hidden input
            updateOTPValue();

            // Move to next input if value exists
            if (e.target.value.length === 1) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                } else {
                    verifyBtn.focus();
                }
            }
        });

        // Handle Key navigation (Backspace/Arrows)
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
            if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
            }
            if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Handle Paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^[0-9]+$/.test(text)) return;

            const digits = text.split('');
            inputs.forEach((inp, i) => {
                if (digits[i]) {
                    inp.value = digits[i];
                    if (i < inputs.length - 1) inputs[i+1].focus();
                }
            });
            updateOTPValue();
        });
    });

    // Update hidden OTP input
    function updateOTPValue() {
        const otp = Array.from(inputs).map(input => input.value).join('');
        otpValue.value = otp;
    }

    // Timer Logic
    function startTimer() {
        resendBtn.disabled = true;
        timeLeft = 59;
        timerDisplay.classList.remove('text-red-500');

        clearInterval(timerId);
        timerId = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timerId);
                resendBtn.disabled = false;
                timerDisplay.textContent = "00:00";
                timerDisplay.classList.add('text-red-500');
            } else {
                timerDisplay.textContent = `00:${timeLeft.toString().padStart(2, '0')}`;
                timeLeft--;
            }
        }, 1000);
    }

    function resetTimer() {
        axios.post('{{ route("") }}')
            .catch(err => console.error(err));

        inputs.forEach(i => i.value = '');
        inputs[0].focus();
        updateOTPValue();
        startTimer();
    }

    // Initialize Timer
    startTimer();

    // Form validation before submit
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        const otp = Array.from(inputs).map(input => input.value).join('');

        if (otp.length !== 6) {
            e.preventDefault();
            const container = document.getElementById('otp-container');
            container.classList.add('shake');
            setTimeout(() => container.classList.remove('shake'), 500);
            return false;
        }

        // Show loading state
        verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i> Verifying...';
        verifyBtn.disabled = true;
    });
