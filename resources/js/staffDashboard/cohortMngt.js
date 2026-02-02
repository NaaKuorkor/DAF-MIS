// resources/js/staffDashboard/cohortMngt.js

export default function loadCohorts() {
    console.log("cohortMngt loaded");

    let currentCourseId = null;
    let currentCourseName = null;
    let eventListeners = [];
    let globalFunctions = {};

    // DOM elements
    const courseCardsGrid = document.getElementById('courseCardsGrid');
    const coursesView = document.getElementById('coursesView');
    const cohortsTableView = document.getElementById('cohortsTableView');
    const searchInput = document.getElementById('searchCourses');
    const createCohortForm = document.getElementById('createCohortForm');
    const createCohortModal = document.getElementById('createCohortModal');
    const backToCoursesBtn = document.getElementById('backToCoursesBtn');
    const createCohortHeaderBtn = document.getElementById('createCohortHeaderBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');

    if (!courseCardsGrid || !coursesView || !cohortsTableView) {
        console.warn('Cohort management elements not found');
        return () => { };
    }

    // Initialize
    loadCourses();
    setupEventListeners();

    // Format course data to include computed fields for UI
    function formatCourseData(course) {
        return {
            course_id: course.course_id,
            course_name: course.course_name,
            description: course.description || '',
            status: 'Active', // Compute based on dates/registrations if needed
            start_date: course.createdate || new Date().toISOString(),
            end_date: null,
            registered: 0, // Get from course registrations count
            limit: null,
            color: 'purple',
            icon: 'fa-book'
        };
    }

    async function loadCourses() {
        try {
            const response = await axios.get('/staff/viewCourses');
            const coursesData = response.data.data || (Array.isArray(response.data) ? response.data : []);

            // Map courses to include computed fields
            const courses = Array.isArray(coursesData)
                ? coursesData.map(formatCourseData)
                : [];

            renderCourseCards(courses);
        } catch (error) {
            console.error('Error loading courses:', error);
            courseCardsGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                    <p class="text-red-500">Failed to load courses</p>
                </div>
            `;
        }
    }

    function renderCourseCards(courses) {
        if (!courseCardsGrid) return;

        if (!courses || courses.length === 0) {
            courseCardsGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No courses found</p>
                </div>
            `;
            return;
        }

        courseCardsGrid.innerHTML = courses.map(course => createCourseCard(course)).join('');

        // Attach event listeners to buttons
        courses.forEach(course => {
            const viewBtn = document.querySelector(`[data-view-cohorts="${course.course_id}"]`);
            const openBtn = document.querySelector(`[data-open-new="${course.course_id}"]`);

            if (viewBtn) {
                const handler = () => viewCohorts(course.course_id, course.course_name);
                viewBtn.addEventListener('click', handler);
                eventListeners.push({ element: viewBtn, event: 'click', handler });
            }
            if (openBtn) {
                const handler = () => openNewCohortModal(course.course_id, course.course_name);
                openBtn.addEventListener('click', handler);
                eventListeners.push({ element: openBtn, event: 'click', handler });
            }
        });
    }

    function createCourseCard(course) {
        const statusBadge = getStatusBadge(course.status);
        const cardContent = (course.status === 'Upcoming' || course.status === 'Closing Soon')
            ? createUpcomingContent(course)
            : '';

        return `
            <div class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md hover:border-purple-200 transition-all duration-200 flex flex-col h-full">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                            <i class="fa ${course.icon || 'fa-book'} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 tracking-tight leading-tight">${course.course_name}</h3>
                            <p class="text-xs font-medium text-slate-500 mt-1">${course.course_id}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        ${statusBadge}
                    </div>
                </div>

                ${cardContent}

                <div class="mt-auto grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <button data-view-cohorts="${course.course_id}" class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fa fa-layer-group"></i>
                        View Cohorts
                    </button>
                    <button data-open-new="${course.course_id}" class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-purple-600 bg-purple-50 border border-transparent rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fa fa-plus"></i>
                        Open New
                    </button>
                </div>
            </div>
        `;
    }

    function getStatusBadge(status) {
        const badges = {
            'Active': '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-green-50 text-green-700 border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Active</span>',
            'Upcoming': '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Upcoming</span>',
            'Completed': '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Completed</span>',
            'Closing Soon': '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-700 border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Closing Soon</span>'
        };
        return badges[status] || '';
    }


    function createUpcomingContent(course) {
        return `
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3 p-2 bg-slate-50 rounded-lg border border-slate-100">
                    <div class="flex -space-x-2">
                        <div class="w-6 h-6 rounded-full bg-purple-300 border-2 border-white"></div>
                        <div class="w-6 h-6 rounded-full bg-purple-400 border-2 border-white"></div>
                        <div class="w-6 h-6 rounded-full bg-purple-500 border-2 border-white"></div>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">
                        ${course.limit ? `${course.registered || 0}/${course.limit} Filled` : `${course.registered || 0} Students Registered`}
                    </span>
                </div>
                <div class="flex justify-between text-xs text-slate-500 mt-1">
                    <span ${course.status === 'Closing Soon' ? 'class="text-red-500 font-medium"' : ''}>
                        ${course.status === 'Closing Soon' ? 'Closes soon' : `Starts: ${formatDate(course.start_date)}`}
                    </span>
                </div>
            </div>
        `;
    }

    async function viewCohorts(courseId, courseName) {
        currentCourseId = courseId;
        currentCourseName = courseName;

        if (coursesView) coursesView.style.display = 'none';
        if (cohortsTableView) cohortsTableView.style.display = 'block';

        const courseTitle = document.getElementById('courseTitle');
        const courseSubtitle = document.getElementById('courseSubtitle');
        if (courseTitle) courseTitle.textContent = `${courseName} - Cohorts`;
        if (courseSubtitle) courseSubtitle.textContent = `Course ID: ${courseId}`;

        await loadCohortsTable(courseId);
    }

    async function loadCohortsTable(courseId) {
        const tbody = document.getElementById('cohortsTableBody');
        if (!tbody) return;

        // Show loading state
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="p-8 text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-4"></i>
                    <p class="text-gray-500">Loading cohorts...</p>
                </td>
            </tr>
        `;

        try {
            const response = await axios.get(`/staff/cohorts/${courseId}`);
            const data = response.data;

            renderCohortsTable(data.cohorts || []);
        } catch (error) {
            console.error('Error loading cohorts:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="p-8 text-center text-red-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-3"></i>
                        <p class="text-sm">Failed to load cohorts</p>
                    </td>
                </tr>
            `;
        }
    }

    function renderCohortsTable(cohorts) {
        const tbody = document.getElementById('cohortsTableBody');
        if (!tbody) return;

        if (!cohorts || cohorts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        <i class="fa fa-inbox text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm">No cohorts found for this course.</p>
                    </td>
                </tr>
            `;
            updatePaginationInfo(0);
            return;
        }

        tbody.innerHTML = cohorts.map(cohort => `
            <tr class="group hover:bg-slate-50/80 transition-colors">
                <td class="p-4">
                    <input type="checkbox" class="custom-checkbox cursor-pointer">
                </td>
                <td class="p-4">
                    <div>
                        <p class="text-sm font-medium text-slate-900">${cohort.description || 'No description'}</p>
                        <p class="text-xs text-slate-500">ID: ${cohort.cohort_id}</p>
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-slate-700">${formatDate(cohort.start_date)}</div>
                    <div class="text-xs text-slate-400">to ${formatDate(cohort.end_date)}</div>
                </td>
                <td class="p-4">
                    ${getCohortStatusBadge(cohort.status)}
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <i class="fa fa-users text-slate-400"></i>
                        <span class="text-sm font-medium text-slate-900">${cohort.student_count || 0}</span>
                    </div>
                </td>
                <td class="p-4 text-right">
                    <div class="flex items-center justify-end gap-2 transition-opacity">
                        <button onclick="window.viewCohortDetails('${cohort.cohort_id}')" class="p-1.5 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="View Details">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button onclick="window.editCohort('${cohort.cohort_id}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Cohort">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button onclick="window.deleteCohort('${cohort.cohort_id}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Cohort">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        updatePaginationInfo(cohorts.length);
    }

    function updatePaginationInfo(total) {
        const rangeStart = document.getElementById('cohortRangeStart');
        const rangeEnd = document.getElementById('cohortRangeEnd');
        const cohortTotal = document.getElementById('cohortTotal');

        if (rangeStart) rangeStart.textContent = '1';
        if (rangeEnd) rangeEnd.textContent = Math.min(5, total).toString();
        if (cohortTotal) cohortTotal.textContent = total.toString();
    }

    function getCohortStatusBadge(status) {
        const badges = {
            'ongoing': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium">Ongoing</span>',
            'open': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium">Open</span>',
            'completed': '<span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">Completed</span>'
        };
        return badges[status?.toLowerCase()] || '<span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">Unknown</span>';
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

    function backToCourses() {
        if (cohortsTableView) cohortsTableView.style.display = 'none';
        if (coursesView) coursesView.style.display = 'block';
        currentCourseId = null;
        currentCourseName = null;
    }

    function openNewCohortModal(courseId, courseName) {
        currentCourseId = courseId;
        currentCourseName = courseName;

        const modalCourseId = document.getElementById('modal_course_id');
        const modalCourseTitle = document.getElementById('modalCourseTitle');

        if (modalCourseId) modalCourseId.value = courseId;
        if (modalCourseTitle) modalCourseTitle.textContent = `For: ${courseName} (${courseId})`;
        if (createCohortModal) createCohortModal.style.display = 'flex';
    }

    function closeCreateCohortModal() {
        if (createCohortModal) createCohortModal.style.display = 'none';
        if (createCohortForm) createCohortForm.reset();
    }

    function setupEventListeners() {
        // Back to courses button
        if (backToCoursesBtn) {
            const handler = backToCourses;
            backToCoursesBtn.addEventListener('click', handler);
            eventListeners.push({ element: backToCoursesBtn, event: 'click', handler });
        }

        // Create cohort from header button
        if (createCohortHeaderBtn) {
            const handler = () => {
                if (currentCourseId && currentCourseName) {
                    openNewCohortModal(currentCourseId, currentCourseName);
                }
            };
            createCohortHeaderBtn.addEventListener('click', handler);
            eventListeners.push({ element: createCohortHeaderBtn, event: 'click', handler });
        }

        // Modal close buttons
        if (closeModalBtn) {
            const handler = closeCreateCohortModal;
            closeModalBtn.addEventListener('click', handler);
            eventListeners.push({ element: closeModalBtn, event: 'click', handler });
        }

        if (cancelModalBtn) {
            const handler = closeCreateCohortModal;
            cancelModalBtn.addEventListener('click', handler);
            eventListeners.push({ element: cancelModalBtn, event: 'click', handler });
        }

        // Close modal on backdrop click
        if (createCohortModal) {
            const handler = (e) => {
                if (e.target === createCohortModal) {
                    closeCreateCohortModal();
                }
            };
            createCohortModal.addEventListener('click', handler);
            eventListeners.push({ element: createCohortModal, event: 'click', handler });
        }

        // Form submission
        if (createCohortForm) {
            const handler = handleCreateCohort;
            createCohortForm.addEventListener('submit', handler);
            eventListeners.push({ element: createCohortForm, event: 'submit', handler });
        }

        // Search functionality
        if (searchInput) {
            const handler = handleSearch;
            searchInput.addEventListener('input', handler);
            eventListeners.push({ element: searchInput, event: 'input', handler });
        }
    }

    async function handleCreateCohort(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitCohortBtn');
        if (!submitBtn) return;

        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating...';
        submitBtn.disabled = true;

        const formData = new FormData(e.target);

        try {
            const response = await axios.post('/staff/createCohort', formData);

            if (response.data.success) {
                toast.success('Cohort created successfully!');
                closeCreateCohortModal();

                // Reload cohorts table if we're viewing them
                if (currentCourseId && cohortsTableView && cohortsTableView.style.display !== 'none') {
                    await loadCohortsTable(currentCourseId);
                }
            } else {
                toast.error(response.data.message || 'Failed to create cohort');
            }
        } catch (error) {
            console.error('Error creating cohort:', error);
            if (error.response?.data?.errors) {
                const errors = Object.values(error.response.data.errors).flat();
                toast.error(errors.join('\n'));
            } else {
                toast.error(error.response?.data?.message || 'An error occurred while creating the cohort');
            }
        } finally {
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
        }
    }

    async function handleSearch(e) {
        const searchTerm = e.target.value.trim();

        if (searchTerm === '') {
            await loadCourses();
            return;
        }

        try {
            const response = await axios.get(`/staff/search?q=${encodeURIComponent(searchTerm)}`);
            const coursesData = response.data.data || (Array.isArray(response.data) ? response.data : []);
            const courses = Array.isArray(coursesData)
                ? coursesData.map(formatCourseData)
                : [];
            renderCourseCards(courses);
        } catch (error) {
            console.error('Error searching courses:', error);
        }
    }

    // Global functions for table actions
    globalFunctions.viewCohortDetails = async function (cohortId) {
        console.log('View cohort details:', cohortId);
        // Implement view details functionality - you can open a modal or navigate
        // For now, just log it
    };

    globalFunctions.editCohort = async function (cohortId) {
        console.log('Edit cohort:', cohortId);
        // Implement edit functionality - you can populate the create modal or open an edit modal
    };

    globalFunctions.deleteCohort = async function (cohortId) {
        if (!confirm('Are you sure you want to delete this cohort? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await axios.delete(`/staff/cohorts/${cohortId}`);

            if (response.data.success) {
                toast.success('Cohort deleted successfully!');
                if (currentCourseId) {
                    await loadCohortsTable(currentCourseId);
                }
            } else {
                toast.error(response.data.message || 'Failed to delete cohort');
            }
        } catch (error) {
            console.error('Error deleting cohort:', error);
            toast.error(error.response?.data?.message || 'Failed to delete cohort');
        }
    };

    // Make global functions available on window object
    window.viewCohortDetails = globalFunctions.viewCohortDetails;
    window.editCohort = globalFunctions.editCohort;
    window.deleteCohort = globalFunctions.deleteCohort;

    // Cleanup function
    return function cleanup() {
        // Remove all event listeners
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        eventListeners = [];

        // Remove global functions
        delete window.viewCohortDetails;
        delete window.editCohort;
        delete window.deleteCohort;

        // Reset state
        currentCourseId = null;
        currentCourseName = null;

        console.log('Cohort module cleaned up');
    };
}
