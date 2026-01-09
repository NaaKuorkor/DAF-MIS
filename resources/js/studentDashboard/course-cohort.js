export default function loadCourses() {
    const statsGrid = document.getElementById('course-stats-grid');
    const detailsSection = document.getElementById('course-details-section');
    
    // Early return if essential elements not found
    if (!statsGrid) {
        console.warn('Course-cohort elements not found');
        return () => {}; // Return empty cleanup function
    }

    // Track any state that might need cleanup
    let isInitialized = true;

    // Fetch and display course data
    fetchCourseData();

    // Return cleanup function
    return function cleanup() {
        // Reset state
        isInitialized = false;

        // Clear any displayed content if needed
        if (statsGrid) {
            statsGrid.innerHTML = '';
        }

        if (detailsSection) {
            detailsSection.style.display = 'none';
        }

        console.log('Course-cohort module cleaned up');
    };
}

async function fetchCourseData() {
    const statsGrid = document.getElementById('course-stats-grid');
    
    if (!statsGrid) {
        console.warn('Stats grid not found');
        return;
    }

    try {
        const response = await axios.get('/indexcourse-cohort');
        console.log('Course Data:', response.data);

        if (response.data.success) {
            displayCourseCards(response.data.data);
        } else {
            showError('Failed to load course information');
        }
    } catch (err) {
        console.error('Error fetching course data:', err);
        showError('Failed to load course information');
    }
}

function displayCourseCards(data) {
    const { course, cohort } = data;
    const statsGrid = document.getElementById('course-stats-grid');

    if (!statsGrid) {
        console.warn('Stats grid not found');
        return;
    }

    // Build cards HTML
    const cards = [];

    // Course Card
    if (course) {
        cards.push({
            icon: 'fa-book-open',
            bg_color: 'bg-blue-50',
            text_color: 'text-blue-600',
            title: 'Current Course',
            value: course.course_name || 'N/A',
            subtitle: course.course_id || ''
        });
    }

    // Cohort Card - Check if cohort exists via cohort_registration
    cards.push({
        icon: 'fa-users',
        bg_color: 'bg-purple-50',
        text_color: 'text-purple-600',
        title: 'Cohort',
        value: cohort ? (cohort.description || cohort.cohort_id || 'Not Assigned') : 'Not Assigned',
        subtitle: cohort ? (cohort.cohort_id || '') : ''
    });

    // Academic Year Card
    cards.push({
        icon: 'fa-calendar-alt',
        bg_color: 'bg-green-50',
        text_color: 'text-green-600',
        title: 'Academic Year',
        value: new Date().getFullYear().toString(),
        subtitle: ''
    });

    // Status Card
    cards.push({
        icon: 'fa-check-circle',
        bg_color: 'bg-amber-50',
        text_color: 'text-amber-600',
        title: 'Status',
        value: 'Active',
        subtitle: ''
    });

    // Render cards
    let cardsHTML = '';
    cards.forEach(card => {
        cardsHTML += `
            <div class="bg-white p-6 rounded-xl border border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg ${card.bg_color} flex items-center justify-center ${card.text_color}">
                        <i class="fas ${card.icon} text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 font-medium">${card.title}</p>
                <h3 class="text-2xl font-semibold text-gray-900 mt-1 tracking-tight">${card.value}</h3>
                ${card.subtitle ? `<p class="text-xs text-gray-400 mt-1">${card.subtitle}</p>` : ''}
            </div>
        `;
    });

    statsGrid.innerHTML = cardsHTML;

    // Display course details section
    displayCourseDetails(course, cohort);
}

function displayCourseDetails(course, cohort) {
    const detailsSection = document.getElementById('course-details-section');
    const courseInfoContent = document.getElementById('course-info-content');
    const cohortInfoContent = document.getElementById('cohort-info-content');

    if (!detailsSection) {
        console.warn('Details section not found');
        return;
    }

    // Show the section
    detailsSection.style.display = 'grid';

    // Populate course information
    if (courseInfoContent) {
        if (course) {
            courseInfoContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Course Name</p>
                        <p class="text-sm font-medium text-gray-900">${course.course_name || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Course Code</p>
                        <p class="text-sm font-medium text-gray-900">${course.course_id || 'N/A'}</p>
                    </div>
                    ${course.description ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Description</p>
                        <p class="text-sm text-gray-700">${course.description}</p>
                    </div>
                    ` : ''}
                    ${course.duration ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Duration</p>
                        <p class="text-sm font-medium text-gray-900">${course.duration}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            courseInfoContent.innerHTML = '<p class="text-sm text-gray-500">No course information available</p>';
        }
    }

    // Populate cohort information
    if (cohortInfoContent) {
        if (cohort) {
            cohortInfoContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Cohort ID</p>
                        <p class="text-sm font-medium text-gray-900">${cohort.cohort_id || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Description</p>
                        <p class="text-sm font-medium text-gray-900">${cohort.description || 'N/A'}</p>
                    </div>
                    ${cohort.start_date ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Start Date</p>
                        <p class="text-sm font-medium text-gray-900">${formatDate(cohort.start_date)}</p>
                    </div>
                    ` : ''}
                    ${cohort.end_date ? `
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">End Date</p>
                        <p class="text-sm font-medium text-gray-900">${formatDate(cohort.end_date)}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            cohortInfoContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900 mb-1">No Cohort Assigned</p>
                    <p class="text-xs text-gray-500">You haven't been assigned to a cohort yet</p>
                </div>
            `;
        }
    }
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'N/A';
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    } catch (e) {
        return 'N/A';
    }
}

function showError(message) {
    const statsGrid = document.getElementById('course-stats-grid');
    
    if (!statsGrid) {
        console.warn('Stats grid not found');
        return;
    }

    statsGrid.innerHTML = `
        <div class="col-span-full bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
                <div>
                    <h3 class="text-red-900 font-semibold">${message}</h3>
                    <p class="text-red-700 text-sm mt-1">Please refresh the page or contact support if the problem persists.</p>
                </div>
            </div>
        </div>
    `;
}