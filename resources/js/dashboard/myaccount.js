export async function loadProfileDetails(){
    const editBtn = document.getElementById('editBtn');
    const actionBtns = document.getElementById('actionBtns');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const inputs = document.querySelectorAll('.profileInput');
    const selects = document.querySelectorAll('.profileSelect');
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    const changeBtn = document.getElementById('changeBtn');

    let originalInput = {};

    function showActionBtns(){
        actionBtns.hidden = false;

        originalInput = {}

        inputs.forEach(input => {
            originalInput[input.name] = input.value;
            input.removeAttribute('readonly');
        });

        selects.forEach(select => {
            originalInput[select.name] = select.value;
            select.removeAttribute('disabled');
        })
    }

    function hideActionBtns(restore = false){
        actionBtns.hidden = true;

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
        });

        selects.forEach(select => {
            select.setAttribute('disabled', true);
        });
    }

    async function handleProfileSubmit(e){
        e.preventDefault();
        saveBtn.innerHTML = 'Saving...'
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
            saveBtn.innerHTML ="Save";
            saveBtn.disabled = false;
        }
    }

    async function handlePasswordSubmit(e){
        e.preventDefault();
        changeBtn.innerHTML = 'Making changes...';
        changeBtn.disabled = true;

        const formData = new FormData(passwordForm);

        try{
             const response = await axios.post(passwordForm.action, formData);

            alert(response.data.message);

        }catch(error){
             if (error.response?.data?.errors) {
                Object.values(error.response.data.errors)
                    .forEach(err => alert(err[0]));
            } else {
                alert("Update failed");
            }
        }finally{
            changeBtn.innerHTML = 'Change';
            changeBtn.disabled = false;
        }

    }



    editBtn.addEventListener('click', showActionBtns);
    cancelBtn.addEventListener('click', () => hideActionBtns(true));
    profileForm.addEventListener('submit', handleProfileSubmit);
}
