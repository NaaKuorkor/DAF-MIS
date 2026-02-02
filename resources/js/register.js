import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    const first = document.getElementById('first');
    const second = document.getElementById('second');
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    const form = document.querySelector('form[action*="register"]');

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

    // Handle form submission with AJAX
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';

            // Clear previous errors
            const errorContainer = document.getElementById('error-container');
            const successContainer = document.getElementById('success-container');
            if (errorContainer) errorContainer.remove();
            if (successContainer) successContainer.remove();

            try {
                const formData = new FormData(form);
                
                const response = await axios.post(form.action, formData, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.data.success) {
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.id = 'success-container';
                    successDiv.className = 'mb-4 p-4 bg-green-50 border border-green-200 rounded-lg';
                    successDiv.innerHTML = `
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <p class="text-sm text-green-800 font-medium">${response.data.message || 'Registration successful!'}</p>
                        </div>
                        <p class="text-xs text-green-700 mt-2">Please check your email for verification link.</p>
                    `;
                    form.insertBefore(successDiv, form.firstChild);

                    // Redirect to email verification notice after 3 seconds
                    setTimeout(() => {
                        window.location.href = '/email/verify';
                    }, 3000);
                } else {
                    throw new Error(response.data.message || 'Registration failed');
                }
            } catch (error) {
                console.error('Registration error:', error);
                
                let errorMessage = 'Registration failed. Please try again.';
                let errors = {};

                if (error.response) {
                    if (error.response.data) {
                        if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                        if (error.response.data.errors) {
                            errors = error.response.data.errors;
                        }
                    }
                } else if (error.message) {
                    errorMessage = error.message;
                }

                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.id = 'error-container';
                errorDiv.className = 'mb-4 p-4 bg-red-50 border border-red-200 rounded-lg';
                
                let errorHtml = `
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                        <p class="text-sm text-red-800 font-medium">${errorMessage}</p>
                    </div>
                `;

                // Add validation errors if any
                if (Object.keys(errors).length > 0) {
                    errorHtml += '<ul class="list-disc list-inside text-xs text-red-700 mt-2 space-y-1">';
                    Object.entries(errors).forEach(([field, messages]) => {
                        if (Array.isArray(messages)) {
                            messages.forEach(msg => {
                                errorHtml += `<li>${field}: ${msg}</li>`;
                            });
                        } else {
                            errorHtml += `<li>${field}: ${messages}</li>`;
                        }
                    });
                    errorHtml += '</ul>';
                }

                errorDiv.innerHTML = errorHtml;
                form.insertBefore(errorDiv, form.firstChild);

                // Scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

})
