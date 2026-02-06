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
import Chart from 'chart.js/auto';

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

    // Chart instance variable
    let overviewChart = null;

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
                await displayOverview(false); // Pass false to avoid reloading content via axios again if not needed, but here we kind of already loaded it via axios.get(route).
                // Actually displayOverview() calls axios.get('/overview') which is redundant if we already called axios.get(route).
                // However, the original code had:
                // if (moduleRegistry[route]) { ... } else if (route === '/staff/overview') { await displayOverview(); }
                // Use initOverviewChart directly since content is already loaded? 
                // Wait, displayOverview fetches /overview. The current route '/staff/overview' fetches getContent('/staff/overview') which returns the same view.
                // So I can just call initOverviewChart() here.
                initOverviewChart();
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

    async function displayOverview(fetch = true) {
        if (fetch) {
            try {
                const response = await axios.get('/overview');
                dashboardContent.innerHTML = `<div class="content-fade">${response.data}</div>`;
            } catch (err) {
                console.error('Failed to load overview:', err);
                dashboardContent.innerHTML = `<p class="text-red-500">Failed to load overview.</p>`;
                return;
            }
        }
        initOverviewChart();
    }

    function initOverviewChart() {
        const chartCanvas = document.getElementById('registrationChart');
        const dataElement = document.getElementById('registrationChartData');

        if (!chartCanvas || !dataElement) return;

        // Destroy existing chart if it exists
        if (overviewChart) {
            overviewChart.destroy();
            overviewChart = null;
        }

        try {
            const registrationData = JSON.parse(dataElement.dataset.registrations);

            overviewChart = new Chart(chartCanvas, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Student Registrations',
                        data: registrationData,
                        backgroundColor: 'rgba(147, 51, 234, 0.6)',
                        borderColor: 'rgb(147, 51, 234)',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(147, 51, 234, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                family: "'Inter', sans-serif"
                            },
                            bodyFont: {
                                size: 14,
                                family: "'Inter', sans-serif"
                            },
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif"
                                },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif"
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        } catch (e) {
            console.error("Error initializing chart:", e);
        }
    }

    // Initialize
    getModules();
    // Use displayOverview(true) to fetch and render
    displayOverview(true);

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
        if (overviewChart) {
            overviewChart.destroy();
        }
    });
});
