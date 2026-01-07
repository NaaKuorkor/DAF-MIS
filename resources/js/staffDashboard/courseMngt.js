export default function loadCourses() {
    console.log("courseMngt is loaded");

    const courseGrid = document.getElementById('courseGrid');
    const searchCourse = document.getElementById('searchCourse');
    const createCourseBtn = document.getElementById('createCourseBtn');
    const courseView = document.getElementById('course-view');
    const registrationView = document.getElementById('registration-view');

    let currentCourse = null;

    // Load all courses
    async function loadAllCourses() {
        try {
            const response = await axios.get('/staff/viewCourses');
            renderCourseGrid(response.data.data);
        } catch (err) {
            console.error('Failed to load courses:', err);
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
    }

    // Search functionality
    async function searchCourses() {
        const query = this.value;
        try {
            const response = await axios.get('/courses/search?q=' + encodeURIComponent(query));
            renderCourseGrid(response.data.data);
        } catch (err) {
            console.error('Search failed:', err);
        }
    }

    // View course registrations
    window.viewCourseRegistrations = async function(courseId) {
        currentCourse = courseId;
        try {
            const response = await axios.get(`/courses/${courseId}/registrations`);
            renderRegistrationView(response.data.data, courseId);
            courseView.classList.add('hidden');
            registrationView.classList.remove('hidden');
        } catch (err) {
            console.error('Failed to load registrations:', err);
            alert('Failed to load registrations');
        }
    };

    // Render registration view
    function renderRegistrationView(registrations, courseId) {
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
                                <button class="px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                    <i class="fas fa-plus-circle mr-1"></i>
                                    Assign to Cohort
                                </button>
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
    }

    // Back to courses
    window.backToCourses = function() {
        registrationView.classList.add('hidden');
        courseView.classList.remove('hidden');
        currentCourse = null;
    };

    // Edit button
    function getEditButton(course) {
        return `
            <button onclick='window.editCourse(${JSON.stringify(course).replace(/'/g, "\\'")} )' class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Edit Course">
                <i class="fas fa-pencil text-sm"></i>
            </button>
        `;
    }

    // Delete button
    function getDeleteButton(course) {
        return `
            <button onclick='window.deleteCourse("${course.course_id}")' class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Course">
                <i class="fas fa-trash text-sm"></i>
            </button>
        `;
    }

    // Delete course
    window.deleteCourse = async function(courseId) {
        if (!confirm('Are you sure you want to delete this course?')) return;

        try {
            const response = await axios.delete(`/courses/${courseId}`);
            if (response.data.success) {
                alert(response.data.message);
                loadAllCourses();
            }
        } catch (err) {
            console.error('Delete failed:', err);
            alert('Failed to delete course');
        }
    };

    // Edit course (implement modal similar to create)
    window.editCourse = function(course) {
        // You can implement an edit modal similar to create modal
        console.log('Edit course:', course);
        alert('Edit functionality - to be implemented with modal');
    };

    // Create course modal trigger
    if (createCourseBtn) {
        createCourseBtn.addEventListener('click', () => {
            // Trigger Alpine.js modal
            const modal = document.querySelector('[x-data*="modalOpen"]');
            if (modal) {
                Alpine.$data(modal).modalOpen = true;
            }
        });
    }

    // Event listeners
    if (searchCourse) searchCourse.addEventListener('input', searchCourses);

    // Initial load
    loadAllCourses();
}
