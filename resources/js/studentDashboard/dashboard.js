// resources/js/studentDashboard/dashboard.js

import moduleLoader from '../core/moduleLoader.js';
import loadCourses from './course-cohort.js';
import loadStudentProfile from './myaccount.js';
import loadStudentAnnouncements from './announcements.js';
import initStudentAnnouncementNotifications from './announcementNotifications.js';

// Module registry - maps routes to their loaders
const moduleRegistry = {
    '/course-cohort': loadCourses,
    '/myProfile': loadStudentProfile,
    '/announcements': loadStudentAnnouncements,
};

document.addEventListener('DOMContentLoaded', () => {
    const navigationMenu = document.getElementById('studentNavigation-menu');
    const dashboardContent = document.getElementById('studentDashboardContent');

    if (!navigationMenu || !dashboardContent) {
        console.warn('Student dashboard elements not found');
        return;
    }

    // Icon mapping for modules
    const iconMap = {
        'Courses and Cohorts': 'fa-book-open',
        'Announcements': 'fa-bullhorn',
        'Profile': 'fa-user',
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

            // Load corresponding module using module loader
            if (moduleRegistry[route]) {
                await moduleLoader.loadModule(route, async () => {
                    return moduleRegistry[route]();
                });
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

    // Initialize - load first module
    async function initializeDashboard() {
        await getModules();
        // Wait a bit for modules to render, then load first module
        setTimeout(() => {
            const firstButton = navigationMenu.querySelector('button');
            if (firstButton) {
                firstButton.click();
            } else {
                // Fallback: if no modules, try to load course-cohort directly
                displayContent('/course-cohort');
            }
        }, 300);
    }

    // Start initialization
    initializeDashboard();

    // Initialize announcement notifications
    let announcementNotificationsCleanup = null;
    try {
        announcementNotificationsCleanup = initStudentAnnouncementNotifications();
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
