// resources/js/staffDashboard/dashboard.js
import moduleLoader from '../core/moduleLoader.js';
import loadStudents from './studentMngt.js';
import loadStaff from './staffMngt.js';
import loadProfileDetails from './myaccount.js';
import loadCourses from './courseMngt.js';
import loadAnnouncements from './announcements.js';
import loadCohorts from './cohortMngt.js';
import loadTasks from './taskMngt.js';
import initAnnouncementNotifications from './announcementNotifications.js';

// Module registry - maps routes to their loaders
const moduleRegistry = {
    '/staff/student-info': loadStudents,
    '/staff/staff-info': loadStaff,
    '/staff/staffProfile': loadProfileDetails,
    '/staff/courses': loadCourses,
    '/staff/announcements': loadAnnouncements,
    '/staff/cohorts': loadCohorts,
    '/staff/tasks': loadTasks,
};

document.addEventListener('DOMContentLoaded', () => {
    const navigationMenu = document.getElementById('navigation-menu');
    const dashboardContent = document.getElementById('dashboardContent');

    if (!navigationMenu || !dashboardContent) {
        console.warn('Dashboard elements not found');
        return;
    }

    // Icon mapping for modules
    const iconMap = {
        'Overview': 'fa-home',
        'Students': 'fa-users',
        'Staff': 'fa-user-tie',
        'Courses': 'fa-book-open',
        'Cohorts': 'fa-book-open',
        'My Account': 'fa-user',
        'Tasks': 'fa-tasks',
        'Announcements': 'fa-bullhorn'
    };

    async function getModules() {
        try {
            const response = await axios.get('/modules');
            const modules = response.data;
            displayModules(modules);
        } catch (err) {
            console.error('Failed to load modules:', err);
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
            button.className = "nav-item flex items-center px-3 py-2.5 rounded-lg text-purple-200 hover:bg-purple-900 hover:text-white transition-colors group w-full text-left relative";

            button.addEventListener('click', () => {
                setActiveButton(button);
                displayContent(module.mod_url);
            });

            navigationMenu.appendChild(button);

            // Add section divider before personal items
            if (module.mod_label === 'Profile') {
                const divider = document.createElement('div');
                divider.className = "pt-4 pb-2";
                divider.innerHTML = '<p class="px-3 text-xs font-medium text-purple-300 uppercase tracking-wider nav-text">Personal</p>';
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
            dashboardContent.innerHTML = `<div class="content-fade">${response.data}</div>`;

            // Close any open modals when switching modules
            if (window.Alpine) {
                // Close all modals by finding elements with x-show="modalOpen"
                document.querySelectorAll('[x-show*="modalOpen"]').forEach(modal => {
                    try {
                        const parent = modal.closest('[x-data]');
                        if (parent) {
                            const data = Alpine.$data(parent);
                            if (data && typeof data.modalOpen !== 'undefined') {
                                data.modalOpen = false;
                            }
                        }
                    } catch (e) {
                        // Ignore errors
                    }
                });
                // Also try direct approach
                document.querySelectorAll('[x-data]').forEach(element => {
                    try {
                        const data = Alpine.$data(element);
                        if (data && typeof data.modalOpen !== 'undefined' && data.modalOpen === true) {
                            data.modalOpen = false;
                        }
                    } catch (e) {
                        // Ignore errors
                    }
                });
            }

            // Load corresponding module using module loader
            if (moduleRegistry[route]) {
                await moduleLoader.loadModule(route, async () => {
                    return moduleRegistry[route]();
                });
            } else if (route === '/staff/overview') {
                await displayOverview();
            }
        } catch (err) {
            console.error('Failed to load content:', err);
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
            btn.classList.remove('bg-purple-900', 'text-white');
            btn.classList.add('text-purple-200');
            const indicator = btn.querySelector('.absolute');
            if (indicator) indicator.remove();
        });

        activeBtn.classList.remove('text-purple-200');
        activeBtn.classList.add('bg-purple-900', 'text-white');

        const indicator = document.createElement('div');
        indicator.className = "absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-l-full";
        activeBtn.appendChild(indicator);
    }

    async function displayOverview() {
        try {
            const response = await axios.get('/overview');
            dashboardContent.innerHTML = `<div class="content-fade">${response.data}</div>`;
        } catch (err) {
            console.error('Failed to load overview:', err);
            dashboardContent.innerHTML = `<p class="text-red-500">Failed to load overview.</p>`;
        }
    }

    // Initialize
    getModules();
    displayOverview();
    
    // Initialize announcement notifications
    let announcementNotificationsCleanup = null;
    try {
        announcementNotificationsCleanup = initAnnouncementNotifications();
    } catch (error) {
        console.error('Failed to initialize announcement notifications:', error);
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (announcementNotificationsCleanup) {
            announcementNotificationsCleanup();
        }
    });
});