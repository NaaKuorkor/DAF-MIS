// resources/js/studentDashboard/myaccount.js

export default async function loadStudentProfileDetails(){
    const editBtn = document.getElementById('editBtn');
    const actionBtns = document.getElementById('actionBtns');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const inputs = document.querySelectorAll('.profileInput');
    const selects = document.querySelectorAll('.profileSelect');
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    const changeBtn = document.getElementById('changeBtn');
    const toggleButtons = document.querySelectorAll('[data-toggle]');

    // Early return if essential elements not found
    if (!editBtn || !profileForm) {
        console.warn('Profile elements not found');
        return () => {}; // Return empty cleanup function
    }

    // Track event listeners for cleanup
    const eventListeners = [];
    let originalInput = {};

    function showActionBtns(){
        if (!actionBtns || !editBtn) return;
        
        actionBtns.style.display = 'flex';
        editBtn.style.display = 'none';

        originalInput = {};

        inputs.forEach(input => {
            originalInput[input.name] = input.value;
            input.removeAttribute('readonly');
            input.classList.remove('bg-slate-50');
            input.classList.add('bg-white');
        });

        selects.forEach(select => {
            originalInput[select.name] = select.value;
            select.removeAttribute('disabled');
            select.classList.remove('bg-slate-50', 'cursor-not-allowed');
            select.classList.add('bg-white', 'cursor-pointer');
        });
    }

    function hideActionBtns(restore = false){
        if (!actionBtns || !editBtn) return;
        
        actionBtns.style.display = 'none';
        editBtn.style.display = 'flex';

        if (restore) {
            inputs.forEach(input => {
                if (originalInput[input.name] !== undefined) {
                    input.value = originalInput[input.name];
                }
            });

            selects.forEach(select => {
                if (originalInput[select.name] !== undefined) {
                    select.value = originalInput[select.name];
                }
            });
        }

        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.add('bg-slate-50');
            input.classList.remove('bg-white');
        });

        selects.forEach(select => {
            select.setAttribute('disabled', true);
            select.classList.add('bg-slate-50', 'cursor-not-allowed');
            select.classList.remove('bg-white', 'cursor-pointer');
        });
    }

    async function handleProfileSubmit(e){
        e.preventDefault();
        if (!saveBtn) return;
        
        const originalContent = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        const formData = new FormData(profileForm);

        try{
            const response = await axios.post(profileForm.action, formData);
            alert(response.data.message);
            hideActionBtns();
        }catch(error){
            if (error.response?.data?.errors) {
                Object.values(error.response.data.errors)
                    .forEach(err => alert(err[0]));
            } else {
                alert("Update failed");
            }
        }finally{
            saveBtn.innerHTML = originalContent;
            saveBtn.disabled = false;
        }
    }

    function passwordToggle(){
        toggleButtons.forEach(btn => {
            const inputId = btn.dataset.toggle;
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const icon = btn.querySelector('i');
            if (!icon) return;

            const toggleHandler = () => {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            };

            btn.addEventListener('click', toggleHandler);
            eventListeners.push({ element: btn, event: 'click', handler: toggleHandler });
        });
    }

    async function handlePasswordSubmit(e){
        e.preventDefault();
        if (!changeBtn) return;
        
        const originalContent = changeBtn.innerHTML;
        changeBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Changing...';
        changeBtn.disabled = true;

        const formData = new FormData(passwordForm);

        try{
            const response = await axios.post(passwordForm.action, formData);
            alert(response.data.message);
            passwordForm.reset();
        }catch(error){
            if (error.response?.data?.errors) {
                Object.values(error.response.data.errors)
                    .forEach(err => alert(err[0]));
            } else {
                alert("Update failed");
            }
        }finally{
            changeBtn.innerHTML = originalContent;
            changeBtn.disabled = false;
        }
    }

    // Attach event listeners
    editBtn.addEventListener('click', showActionBtns);
    eventListeners.push({ element: editBtn, event: 'click', handler: showActionBtns });

    const cancelHandler = () => hideActionBtns(true);
    if (cancelBtn) {
        cancelBtn.addEventListener('click', cancelHandler);
        eventListeners.push({ element: cancelBtn, event: 'click', handler: cancelHandler });
    }

    profileForm.addEventListener('submit', handleProfileSubmit);
    eventListeners.push({ element: profileForm, event: 'submit', handler: handleProfileSubmit });

    if (passwordForm) {
        passwordForm.addEventListener('submit', handlePasswordSubmit);
        eventListeners.push({ element: passwordForm, event: 'submit', handler: handlePasswordSubmit });
    }

    passwordToggle();

    // Return cleanup function
    return function cleanup() {
        // Remove all event listeners
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });

        // Reset state
        originalInput = {};

        // Ensure forms are in read-only state
        hideActionBtns(false);

        console.log('Student profile module cleaned up');
    };
}