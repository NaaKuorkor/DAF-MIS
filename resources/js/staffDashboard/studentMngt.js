// resources/js/staffDashboard/studentMngt.js

export default function loadStudents() {
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

    const rows = document.getElementById('tableRows');
    const az = document.getElementById('A-Z');
    const date = document.getElementById('date');
    const searchStudent = document.getElementById('searchStudent');
    const showingCount = document.getElementById('showing-count');

    if (!rows) {
        console.warn('Student management elements not found');
        return () => {}; // Return empty cleanup
    }

    const eventListeners = [];

    function renderTable(students) {
        let html = "";

        if (students.length === 0) {
            html = `
            <tr>
                <td colspan="6" class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 font-medium">No students found</p>
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                    </div>
                </td>
            </tr>
            `;
            rows.innerHTML = html;
            if (showingCount) showingCount.textContent = '0';
            return;
        }

        students.forEach(student => {
            const initials = `${student.fname?.charAt(0) || ''}${student.lname?.charAt(0) || ''}`;
            const colors = ['bg-purple-100 text-purple-700', 'bg-blue-100 text-blue-700', 'bg-green-100 text-green-700', 'bg-orange-100 text-orange-700'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];

            html += `
            <tr class="group hover:bg-purple-50/50 transition-colors">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full ${randomColor} flex items-center justify-center font-semibold text-sm flex-shrink-0">
                            ${initials}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${student.name}</p>
                            <p class="text-xs text-gray-500">ID: #${student.studentid || 'N/A'}</p>
                        </div>
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-700">${student.course || 'N/A'}</div>
                </td>
                <td class="p-4">
                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-50 text-purple-700 text-xs font-medium">
                        ${student.cohort || 'N/A'}
                    </span>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-600">${student.registration_date || 'N/A'}</div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-600">${student.referral || 'N/A'}</div>
                </td>
                <td class="p-4 text-right">
                    <div class="flex items-center justify-end gap-2 transition-opacity">
                        ${getEditButton(student)}
                        ${getDeleteButton(student)}
                    </div>
                </td>
            </tr>`;
        });

        rows.innerHTML = html;
        if (showingCount) showingCount.textContent = students.length;
    }

    function getEditButton(student) {
        return `
        <div x-data='{
            modalOpen: false,
            student: ${JSON.stringify(student).replace(/'/g, "\\'")}
        }'>
            <button @click="modalOpen = true" class="p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Edit Student">
                <i class="fas fa-pencil text-lg"></i>
            </button>
            ${getEditModal()}
        </div>`;
    }

    function getEditModal() {
        return `
        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
<div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
<div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">
<div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white">
<h3 class="text-xl font-semibold text-gray-900">Edit Student</h3>
<button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
<i class="fas fa-times text-lg"></i>
</button>
</div>
<form method="POST" action="/staff/updateStudent" @submit.prevent="
axios.post('/staff/updateStudent', new FormData($event.target))
.then(r => { if(r.data.success) { modalOpen=false; toast.success(r.data.message); location.reload(); }})
.catch(e => toast.error(e.response?.data?.message || 'Update failed'))
" class="p-6 max-h-[70vh] overflow-y-auto">
<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
<input type="hidden" name="userid" x-model="student.userid">
<input type="hidden" name="studentid" x-model="student.studentid">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">First Name</label>
                            <input type="text" name="fname" x-model="student.fname" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Middle Name</label>
                            <input type="text" name="mname" x-model="student.mname" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Surname</label>
                            <input type="text" name="lname" x-model="student.lname" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Gender</label>
                            <select name="gender" x-model="student.gender" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Email</label>
                            <input type="email" name="email" x-model="student.email" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Age</label>
                            <input type="number" name="age" x-model="student.age" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Phone</label>
                            <input type="tel" name="phone" x-model="student.phone" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Residence</label>
                            <input type="text" name="residence" x-model="student.residence" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Referral Source</label>
                            <select name="referral" x-model="student.referral" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="Social Media">Social Media</option>
                                <option value="Alumni">DAF Alumni</option>
                                <option value="Website">Website</option>
                                <option value="Institution">Institution</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Employment Status</label>
                            <select name="employment_status" x-model="student.employment_status" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="unemployed">Unemployed</option>
                                <option value="employed">Employed</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Certificate Required</label>
                            <select name="certificate" x-model="student.certificate" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-gray-700">Course</label>
                            <select name="course" x-model="student.course_id" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                <option value="LS101">Life Skills</option>
                            </select>
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

    function getDeleteButton(student) {
        return `
        <div x-data='{
            modalOpen: false,
            student: ${JSON.stringify(student).replace(/'/g, "\\'")}
        }'>
            <button @click="modalOpen = true" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Student">
                <i class="fas fa-trash text-lg"></i>
            </button>
            <template x-teleport="body">
                <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
                    <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                    <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Delete Student?</h3>
                            <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete this student? This action cannot be undone.</p>
                            <div class="flex items-center gap-3">
                                <button @click="modalOpen = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                                <button @click="axios.post('/staff/deleteStudent', {userid: student.userid}).then(r => { if(r.data.success) { modalOpen=false; alert(r.data.message); location.reload(); }}).catch(e => alert(e.response?.data?.message || 'Deletion failed'))" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>`;
    }

    async function studentsDateFilter() {
        try {
            const response = await axios.get('/staff/studentTable/date');
            renderTable(response.data.data);
        } catch (err) {
            console.error('Failed to load student info:', err);
            rows.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-500">Failed to load students</td></tr>`;
        }
    }

    async function studentsAlphabetFilter() {
        try {
            const response = await axios.get('/staff/studentTable/A-Z');
            renderTable(response.data.data);
        } catch (err) {
            console.error('Failed to load student info:', err);
            rows.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-500">Failed to load students</td></tr>`;
        }
    }

    async function search() {
        const query = this.value;
        try {
            const response = await axios.get('/staff/searchStudent?q=' + encodeURIComponent(query));
            renderTable(response.data.data);
        } catch (err) {
            console.error('Search failed:', err);
        }
    }

    // Attach event listeners
    if (searchStudent) {
        searchStudent.addEventListener('input', search);
        eventListeners.push({ element: searchStudent, event: 'input', handler: search });
    }
    if (az) {
        az.addEventListener('click', studentsAlphabetFilter);
        eventListeners.push({ element: az, event: 'click', handler: studentsAlphabetFilter });
    }
    if (date) {
        date.addEventListener('click', studentsDateFilter);
        eventListeners.push({ element: date, event: 'click', handler: studentsDateFilter });
    }

    // Initial load
    studentsDateFilter();

    // Listen for refresh events
    const refreshHandler = () => {
        studentsDateFilter();
    };
    window.addEventListener('refreshStudentTable', refreshHandler);
    eventListeners.push({ element: window, event: 'refreshStudentTable', handler: refreshHandler });

    // Return cleanup function
    return function cleanup() {
        eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        console.log('Student management module cleaned up');
    };
}
