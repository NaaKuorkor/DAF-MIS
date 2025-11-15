
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const dashboardContent = document.getElementById('dashboardContent');

    async function getModules() {
        try{
            //get modules
            const response= await axios.get('/user/modules');
            console.log('Modules:', response.data)
            const modules = response.data;
            //Display modules on dashboard
            displayModules(modules);
        }catch(err) {
            console.error(err);
            sidebar.innerHTML = `<p class="text-red-500">Failed to load.</p>`;
        }
    }

    function displayModules(modules) {
        //Clear the sidebar and re-render modules
        sidebar.innerHTML = "";

        modules.forEach((module) => {
            const button = document.createElement('button');

            button.textContent = module.mod_label;
            button.className = "w-auto bg-gray-200 rounded-lg m-5 p-4";

            //When clicked, change content in the dasboard content section
            button.addEventListener('click', () => {
                activeButton(button);
                displayContent(module.mod_url);
            });
            sidebar.appendChild(button);
        });
    }

    async function displayContent(route) {
        //Clear the content and render new content
        dashboardContent.innerHTML = "";

        try{
             //Get html content
            const response = await axios.get(route);

            //Inject into dashboardContent
            dashboardContent.innerHTML = response.data;

        }catch(err){
            console.log(err);
            dashboardContent.innerHTML = `<p class = 'text-red-500'>Failed to load</p>`
        }


    }

    function activeButton(button){
        //reset all buttons to previous size
        const buttons = sidebar.querySelectorAll('button');

        buttons.forEach((button) => {
            button.classList.remove('scale-105', 'shadow-lg');
            button.classList.add('scale-100', 'shadow-none');
        })

        button.classList.remove('scale-100', 'shadow-none');
        button.classList.add('scale-105', 'shadow-lg');

    }

    async function displayOverview(){
        try{
            //get html for overview
            const response= await axios.get('/staff/overview');
            const cards = response.data;
            //Display content on dashboard
            dashboardContent.innerHTML = cards;
        }catch(err) {
            console.error(err);
            sidebar.innerHTML = `<p class="text-red-500">Failed to load.</p>`;
        }

    }


    getModules();
    displayOverview();
});
