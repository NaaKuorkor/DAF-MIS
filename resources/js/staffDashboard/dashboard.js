import loadStudents from './studentMngt.js';
import loadStaff from './staffMngt.js';
import loadProfileDetails from './myaccount.js';
import loadCourses from './courseMngt.js';

document.addEventListener('DOMContentLoaded', () => {
    const navigationMenu = document.getElementById('navigation-menu');
    const dashboardContent = document.getElementById('dashboardContent');

    // Icon mapping for modules
    const iconMap = {
        'Overview': 'fa-home',
        'Students': 'fa-users',
        'Staff': 'fa-user-tie',
        'Courses': 'fa-book-open',
        'Cohorts': 'fa-book-open',
        'My Account': 'fa-user',
        'Tasks': 'fa-tasks'
    };

    async function getModules() {
        try {
            const response = await axios.get('/modules');
            console.log('Modules:', response.data);
            const modules = response.data;
            displayModules(modules);
        } catch (err) {
            console.error(err);
            navigationMenu.innerHTML = `<p class="text-red-500 text-sm p-4">Failed to load modules.</p>`;
        }
    }

    function displayModules(modules) {
        navigationMenu.innerHTML = "";

        modules.forEach((module, index) => {
            const button = document.createElement('button');
            const icon = iconMap[module.mod_label] || 'fa-circle';

            button.innerHTML = `
                <i class="fas ${icon} text-xl flex-shrink-0"></i>
                <span class="ml-3 font-medium text-sm nav-text">${module.mod_label}</span>
            `;
            button.className = "nav-item flex items-center px-3 py-2.5 rounded-lg text-gray-500 hover:bg-purple-50 hover:text-purple-600 transition-colors group w-full text-left relative";

            button.addEventListener('click', () => {
                setActiveButton(button);
                displayContent(module.mod_url);
            });

            navigationMenu.appendChild(button);

            // Add section divider before personal items
            if (module.mod_label === 'Announcements' || index === modules.length - 3) {
                const divider = document.createElement('div');
                divider.className = "pt-4 pb-2";
                divider.innerHTML = '<p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider nav-text">Personal</p>';
                navigationMenu.appendChild(divider);
            }
        });
    }

    async function displayContent(route) {
        // Show loading state
        dashboardContent.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-4"></i>
                    <p class="text-gray-500">Loading content...</p>
                </div>
            </div>
        `;

        try {
            const response = await axios.get(route);

            // Add fade-in animation
            dashboardContent.innerHTML = `<div class="content-fade">${response.data}</div>`;

            // Load corresponding JS
            if (route === '/staff/student-info') {
                loadStudents();
            } else if (route === '/staff/staff-info') {
                loadStaff();
            } else if (route === '/staff/myAccount') {
                loadProfileDetails();
            }else if (route === '/staff/courses'){
                loadCourses();
            }
        } catch (err) {
            console.log(err);
            dashboardContent.innerHTML = `
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                        <p class="text-red-500">Failed to load content</p>
                    </div>
                </div>
            `;
        }
    }

    function setActiveButton(activeBtn) {
        const buttons = navigationMenu.querySelectorAll('button');
        buttons.forEach((btn) => {
            btn.classList.remove('bg-purple-50', 'text-purple-600');
            btn.classList.add('text-gray-500');
            // Remove active indicator
            const indicator = btn.querySelector('.absolute');
            if (indicator) indicator.remove();
        });

        activeBtn.classList.remove('text-gray-500');
        activeBtn.classList.add('bg-purple-50', 'text-purple-600');

        // Add active indicator
        const indicator = document.createElement('div');
        indicator.className = "absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-purple-600 rounded-l-full";
        activeBtn.appendChild(indicator);
    }

    async function displayOverview() {
        try {
            const response = await axios.get('/overview');
            dashboardContent.innerHTML = `<div class="content-fade">${response.data}</div>`;
        } catch (err) {
            console.error(err);
            dashboardContent.innerHTML = `<p class="text-red-500">Failed to load overview.</p>`;
        }
    }

    // Initialize
    getModules();
    displayOverview();
});
