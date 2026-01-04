export default function loadStaff() {
    console.log("staffMngt is loaded");

    const rows = document.getElementById('staffRows');
    const az = document.getElementById('A-Z');
    const date = document.getElementById('date');
    const searchStaff = document.getElementById('searchStaff');
    const showingCount = document.getElementById('showing-count');

    function renderTable(staff) {
        let html = "";

        if (staff.length === 0) {
            html = `
            <tr>
                <td colspan="4" class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-user-tie text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 font-medium">No staff members found</p>
                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                    </div>
                </td>
            </tr>
            `;
            rows.innerHTML = html;
            if (showingCount) showingCount.textContent = '0';
            return;
        }

        staff.forEach(s => {
            const initials = `${s.fname?.charAt(0) || ''}${s.lname?.charAt(0) || ''}`;
            const colors = ['bg-purple-100 text-purple-700', 'bg-blue-100 text-blue-700', 'bg-green-100 text-green-700', 'bg-orange-100 text-orange-700', 'bg-pink-100 text-pink-700'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];

            const positionBadges = {
                'Senior Lecturer': 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-700/10',
                'Dept. Head': 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
                'Administrator': 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-700/10',
                'default': 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-700/10'
            };
            const badgeClass = positionBadges[s.position] || positionBadges['default'];

            html += `
            <tr class="group hover:bg-purple-50/50 transition-colors">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full ${randomColor} flex items-center justify-center font-semibold text-sm flex-shrink-0">
                            ${initials}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${s.name}</p>
                            <p class="text-xs text-gray-500">${s.email || 'No email'}</p>
                        </div>
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-700">${s.department || 'N/A'}</div>
                </td>
                <td class="p-4">
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium ${badgeClass}">
                        ${s.position || 'Staff'}
                    </span>
                </td>
                <td class="p-4 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        ${getEditButton(s)}
                        ${getDeleteButton(s)}
                    </div>
                </td>
            </tr>`;
        });

        rows.innerHTML = html;
        if (showingCount) showingCount.textContent = staff.length;
    }

    function getEditButton(staff) {
        return `
        <div x-data='{
            modalOpen: false,
            staff: ${JSON.stringify(staff).replace(/'/g, "\\'")}
        }'>
            <button @click="modalOpen = true" class="p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Edit Staff">
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
                        <h3 class="text-xl font-semibold text-gray-900">Edit Staff Member</h3>
                        <button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <form method="POST" action="/staff/updateStaff" @submit.prevent="
                        axios.post('/staff/updateStaff', new FormData($event.target))
                            .then(r => { if(r.data.success) { modalOpen=false; alert(r.data.message); location.reload(); }})
                            .catch(e => alert(e.response?.data?.message || 'Update failed'))
                    " class="p-6 max-h-[70vh] overflow-y-auto">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="userid" x-model="staff.userid">
                        <input type="hidden" name="staffid" x-model="staff.staffid">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">First Name</label>
                                <input type="text" name="fname" x-model="staff.fname" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Middle Name</label>
                                <input type="text" name="mname" x-model="staff.mname" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Surname</label>
                                <input type="text" name="lname" x-model="staff.lname" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Gender</label>
                                <select name="gender" x-model="staff.gender" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Email</label>
                                <input type="email" name="email" x-model="staff.email" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Age</label>
                                <input type="number" name="age" x-model="staff.age" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Phone</label>
                                <input type="tel" name="phone" x-model="staff.phone" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Position</label>
<input type="text" name="position" x-model="staff.position" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
</div>
<div class="space-y-1.5">
<label class="text-xs font-medium text-gray-700">Department</label>
<input type="text" name="department" x-model="staff.department" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
</div>
<div class="space-y-1.5">
<label class="text-xs font-medium text-gray-700">Residence</label>
<input type="text" name="residence" x-model="staff.residence" required class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-600 transition-all">
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

function getDeleteButton(staff) {
    return `
    <div x-data='{
        modalOpen: false,
        staff: ${JSON.stringify(staff).replace(/'/g, "\\'")}
    }'>
        <button @click="modalOpen = true" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Staff">
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
                        <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Delete Staff Member?</h3>
                        <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete this staff member? This action cannot be undone.</p>
                        <div class="flex items-center gap-3">
                            <button @click="modalOpen = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                            <button @click="axios.post('/staff/deleteStaff', {userid: staff.userid}).then(r => { if(r.data.success) { modalOpen=false; alert(r.data.message); location.reload(); }}).catch(e => alert(e.response?.data?.message || 'Deletion failed'))" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>`;
}

async function staffDateFilter() {
    try {
        const response = await axios.get('/staff/staffTable/date');
        renderTable(response.data.data);
    } catch (err) {
        console.error('Failed to load staff info:', err);
        rows.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-red-500">Failed to load staff</td></tr>`;
    }
}

async function staffAlphabeticFilter() {
    try {
        const response = await axios.get('/staff/staffTable/A-Z');
        renderTable(response.data.data);
    } catch (err) {
        console.error('Failed to load staff info:', err);
        rows.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-red-500">Failed to load staff</td></tr>`;
    }
}

async function search() {
    const query = this.value;
    try {
        const response = await axios.get('/staff/searchStaff?q=' + encodeURIComponent(query));
        renderTable(response.data.data);
    } catch (err) {
        console.error('Search failed:', err);
    }
}

// Event listeners
if (searchStaff) searchStaff.addEventListener('input', search);
if (az) az.addEventListener('click', staffAlphabeticFilter);
if (date) date.addEventListener('click', staffDateFilter);

// Initial load
staffDateFilter();
}
