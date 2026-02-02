// resources/js/staffDashboard/courseMngt.js

export default function loadCourses() {
    console.log("courseMngt is loaded");

    // Close any open modals when module loads
    if (window.Alpine) {
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

    const courseGrid = document.getElementById('courseGrid');
    const searchCourse = document.getElementById('searchCourse');
    const createCourseBtn = document.getElementById('createCourseBtn');
    const courseView = document.getElementById('course-view');
    const registrationView = document.getElementById('registration-view');

    // Early return if elements not found
    if (!courseGrid) {
        console.warn('Course management elements not found');
        return () => {}; // Return empty cleanup function
    }

    // Track event listeners for cleanup
    const eventListeners = [];

    // Store references to global functions for cleanup
    const globalFunctions = {
        viewCourseRegistrations: null,
        backToCourses: null,
        deleteCourse: null,
        editCourse: null
    };

    let currentCourse = null;

    // Load all courses
    async function loadAllCourses() {
        try {
            const response = await axios.get('/staff/viewCourses');
            renderCourseGrid(response.data.data);
        } catch (err) {
            console.error('Failed to load courses:', err);
            if (courseGrid) {
                courseGrid.innerHTML = `
                    <div class="col-span-full flex items-center justify-center py-12">
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                            <p class="text-red-500">Failed to load courses</p>
                        </div>
                    </div>
                `;
            }
        }
    }

    // Render course grid
    function renderCourseGrid(courses) {
        if (!courses || courses.length === 0) {
            courseGrid.innerHTML = `
                <div class="col-span-full flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-medium">No courses found</p>
                        <p class="text-sm text-gray-400 mt-1">Create your first course to get started</p>
                    </div>
                </div>
            `;
            return;
        }

        const colors = [
            { bg: 'bg-blue-50', text: 'text-blue-600', icon: 'fa-code' },
            { bg: 'bg-purple-50', text: 'text-purple-600', icon: 'fa-calculator' },
            { bg: 'bg-green-50', text: 'text-green-600', icon: 'fa-flask' },
            { bg: 'bg-orange-50', text: 'text-orange-600', icon: 'fa-globe' },
            { bg: 'bg-pink-50', text: 'text-pink-600', icon: 'fa-palette' },
            { bg: 'bg-teal-50', text: 'text-teal-600', icon: 'fa-atom' }
        ];

        let html = '';
        courses.forEach((course, index) => {
            const colorScheme = colors[index % colors.length];
            html += `
                <div class="bg-white border border-purple-200 rounded-xl p-5 hover:shadow-md hover:border-purple-400 transition-all duration-200 group flex flex-col h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex gap-3 flex-1">
                            <div class="w-10 h-10 rounded-lg ${colorScheme.bg} flex items-center justify-center ${colorScheme.text} flex-shrink-0">
                                <i class="fas ${colorScheme.icon} text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 tracking-tight leading-tight truncate">${course.course_name}</h3>
                                <p class="text-xs font-medium text-gray-500 mt-1">${course.course_id} • ${course.duration || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                        ${course.description || 'No description available'}
                    </p>

                    ${course.eligibility ? `
                    <div class="mb-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-50 text-purple-700 text-xs font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            ${course.eligibility}
                        </span>
                    </div>
                    ` : '<div class="mb-4"></div>'}

                    <div class="mt-auto border-t border-purple-100 pt-4 flex gap-2">
                        <button onclick="window.viewCourseRegistrations('${course.course_id}')" class="flex-1 px-3 py-2 text-xs font-medium text-gray-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 transition-colors flex items-center justify-center gap-1.5">
                            <i class="fas fa-clipboard-list"></i>
                            Registrations
                        </button>
                        ${getEditButton(course)}
                        ${getDeleteButton(course)}
                    </div>
                </div>
            `;
        });

        courseGrid.innerHTML = html;

        // Initialize Alpine.js on the new content
        if (window.Alpine) {
            window.Alpine.initTree(courseGrid);
        }
    }

    // Search functionality
    async function searchCourses() {
        const query = this.value;
        try {
            const response = await axios.get('/staff/search?q=' + encodeURIComponent(query));
            renderCourseGrid(response.data.data);
        } catch (err) {
            console.error('Search failed:', err);
        }
    }

    // View course registrations
    globalFunctions.viewCourseRegistrations = async function(courseId) {
        currentCourse = courseId;
        try {
            const response = await axios.get(`/staff/${courseId}/registrations`);
            renderRegistrationView(response.data.data, courseId);
            if (courseView) courseView.classList.add('hidden');
            if (registrationView) registrationView.classList.remove('hidden');
        } catch (err) {
            console.error('Failed to load registrations:', err);
            toast.error('Failed to load registrations');
        }
    };
    window.viewCourseRegistrations = globalFunctions.viewCourseRegistrations;

    // Reload course registrations (for refreshing after cohort assignment)
    globalFunctions.loadCourseRegistrations = async function(courseId) {
        try {
            const response = await axios.get(`/staff/${courseId}/registrations`);
            renderRegistrationView(response.data.data, courseId);
        } catch (err) {
            console.error('Failed to reload registrations:', err);
            toast.error('Failed to reload registrations');
        }
    };
    window.loadCourseRegistrations = globalFunctions.loadCourseRegistrations;

    // Render registration view
    function renderRegistrationView(registrations, courseId) {
        if (!registrationView) return;

        let html = `
            <div class="content-fade">
                <div class="flex items-center gap-4 mb-6">
                    <button onclick="window.backToCourses()" class="p-2 text-gray-500 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Course Registrations</h1>
                        <p class="text-sm text-gray-500 mt-1">Students enrolled in ${courseId}</p>
                    </div>
                </div>

                <div class="bg-white border border-purple-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-purple-100 bg-purple-50/50">
                                    <th class="p-4">
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Student</div>
                                    </th>
                                    <th class="p-4">
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cohort</div>
                                    </th>
                                    <th class="p-4">
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</div>
                                    </th>
                                    <th class="p-4 text-right">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-purple-100" id="registrationRows">
        `;

        if (!registrations || registrations.length === 0) {
            html += `
                <tr>
                    <td colspan="4" class="p-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-user-graduate text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">No registrations found</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            registrations.forEach(reg => {
                const student = reg.student || {};
                const studentName = student.name || 'Unknown Student';
                const cohort = reg.cohort || 'N/A';
                const registrationDate = new Date(reg.createdate).toLocaleDateString();

                html += `
                    <tr class="group hover:bg-purple-50/50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                    ${studentName.charAt(0)}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">${studentName}</p>
                                    <p class="text-xs text-gray-500">${student.email || 'No email'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            ${cohort === 'N/A' || !cohort ? `
                                <div x-data="{ showDropdown: false, cohorts: [], loading: false, studentid: '${reg.id || reg.studentid || ''}' }" class="relative">
                                    <button @click="
                                        if (!showDropdown && cohorts.length === 0) {
                                            loading = true;
                                            axios.get('/staff/cohorts/${courseId}')
                                                .then(r => {
                                                    cohorts = r.data.cohorts || [];
                                                    showDropdown = true;
                                                    loading = false;
                                                })
                                                .catch(e => {
                                                    toast.error('Failed to load cohorts');
                                                    loading = false;
                                                });
                                        } else {
                                            showDropdown = !showDropdown;
                                        }
                                    " class="px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        <span x-show="!loading">Assign to Cohort</span>
                                        <span x-show="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                                    </button>
                                    <div x-show="showDropdown" @click.away="showDropdown = false" x-cloak class="absolute z-10 mt-2 w-64 bg-white border border-purple-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <div class="p-2">
                                            <template x-if="cohorts.length === 0">
                                                <p class="text-xs text-gray-500 p-2">No cohorts available</p>
                                            </template>
                                            <template x-for="cohort in cohorts" :key="cohort.cohort_id">
                                                <button @click="
                                                    axios.post('/staff/assignToCohort', {
                                                        studentid: studentid,
                                                        cohort_id: cohort.cohort_id
                                                    })
                                                    .then(r => {
                                                        if (r.data.success) {
                                                            toast.success(r.data.message);
                                                            showDropdown = false;
                                                            window.loadCourseRegistrations('${courseId}');
                                                        } else {
                                                            toast.error(r.data.message || 'Assignment failed');
                                                        }
                                                    })
                                                    .catch(e => {
                                                        toast.error(e.response?.data?.message || 'Assignment failed');
                                                    });
                                                " class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded transition-colors">
                                                    <div class="font-medium text-gray-900" x-text="cohort.cohort_id"></div>
                                                    <div class="text-gray-500 text-xs mt-0.5" x-text="cohort.description || 'No description'"></div>
                                                    <div class="text-gray-400 text-xs mt-0.5" x-text="cohort.status + ' • ' + cohort.student_count + ' students'"></div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            ` : `
                                <span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-50 text-purple-700 text-xs font-medium">
                                    ${cohort}
                                </span>
                            `}
                        </td>
                        <td class="p-4">
                            <div class="text-sm text-gray-600">${registrationDate}</div>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="View Details">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        registrationView.innerHTML = html;

        // Initialize Alpine.js on the new content
        if (window.Alpine) {
            window.Alpine.initTree(registrationView);
        }
    }

    // Back to courses
    globalFunctions.backToCourses = function() {
        if (registrationView) registrationView.classList.add('hidden');
        if (courseView) courseView.classList.remove('hidden');
        currentCourse = null;
    };
    window.backToCourses = globalFunctions.backToCourses;

    // Edit button
    function getEditButton(course) {
        const courseData = JSON.stringify(course).replace(/'/g, "\\'");
        return `
        <div x-data='{
            modalOpen: false,
            course: ${courseData}
        }'>
            <button @click="modalOpen = true" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Edit Course">
                <i class="fas fa-pencil text-sm"></i>
            </button>
            ${getEditModal()}
        </div>`;
    }

    function getEditModal() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        return `
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white">
                        <h3 class="text-xl font-semibold text-gray-900">Edit Course</h3>
                        <button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <form method="POST" action="/staff/\${course.course_id}/update" @submit.prevent="
                        axios.post('/staff/' + course.course_id + '/update', new FormData(\$event.target))
                            .then(r => {
                                if(r.data.success) {
                                    modalOpen=false;
                                    loadAllCourses();
                                } else {
                                    toast.error(r.data.message || 'Update failed');
                                }
                            })
                            .catch(e => {
                                console.error('Update error:', e);
                                toast.error(e.response?.data?.message || 'Update failed');
                            })
                    " class="p-6 max-h-[70vh] overflow-y-auto">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="course_id" x-model="course.course_id">
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Course ID</label>
                                <input type="text" x-model="course.course_id" disabled class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Course Name</label>
                                <input type="text" name="course_name" x-model="course.course_name" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Description</label>
                                <textarea name="description" x-model="course.description" rows="3" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-medium text-gray-700">Duration</label>
                                    <input type="text" name="duration" x-model="course.duration" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-medium text-gray-700">Eligibility (Optional)</label>
                                    <input type="text" name="eligibility" x-model="course.eligibility" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-purple-100">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>`;
    }

    // Delete button
    function getDeleteButton(course) {
        return `
        <div x-data='{
            modalOpen: false,
            courseId: "${course.course_id}"
        }'>
            <button @click="modalOpen = true" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Course">
                <i class="fas fa-trash text-sm"></i>
            </button>
            <template x-teleport="body">
                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                    <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                    <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Delete Course?</h3>
                            <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete this course? This action cannot be undone.</p>
                            <div class="flex items-center gap-3">
                                <button @click="modalOpen = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                                <button @click="
                                    axios.delete('/staff/' + courseId)
                                        .then(r => {
                                            if(r.data.success) {
                                                modalOpen=false;
                                                loadAllCourses();
                                            } else {
                                                toast.error(r.data.message || 'Deletion failed');
                                            }
                                        })
                                        .catch(e => {
                                            console.error('Delete error:', e);
                                            toast.error(e.response?.data?.message || 'Deletion failed');
                                        })
                                " class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>`;
    }


    // Create course modal trigger
    if (createCourseBtn) {
        const createCourseHandler = () => {
            // Trigger Alpine.js modal
            const modal = document.querySelector('[x-data*="modalOpen"]');
            if (modal && window.Alpine) {
                Alpine.$data(modal).modalOpen = true;
            }
        };
        createCourseBtn.addEventListener('click', createCourseHandler);
        eventListeners.push({ element: createCourseBtn, event: 'click', handler: createCourseHandler });
    }

    // Event listeners
    if (searchCourse) {
        searchCourse.addEventListener('input', searchCourses);
        eventListeners.push({ element: searchCourse, event: 'input', handler: searchCourses });
    }

    // Initial load
    loadAllCourses();

    // Return cleanup function
    return function cleanup() {
        // Remove all event listeners
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });

        // Clean up global functions
        Object.keys(globalFunctions).forEach(key => {
            if (window[key] === globalFunctions[key]) {
                delete window[key];
            }
        });

        // Reset state
        currentCourse = null;

        console.log('Course management module cleaned up');
    };
}
