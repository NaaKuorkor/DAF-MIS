{{-- resources/views/components/dashboard/staff-management.blade.php --}}
<div class="content-fade">
    <!-- Page Header + Toolbar -->
    <div class="flex flex-col gap-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Staff Management</h1>
                <p class="text-sm text-gray-500 mt-1">Manage faculty members, administrators, and support staff.</p>
            </div>
            <div class="flex gap-3">
                <x-import-staff-modal />
                <button class="px-3 py-2 bg-white border border-purple-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-all shadow-sm flex items-center gap-2 group">
                    <i class="fas fa-download text-gray-500 group-hover:text-gray-700"></i>
                    Export
                </button>
                <x-add-staff-modal />
            </div>
        </div>

        <!-- Filters & Search Toolbar -->
        <div class="flex flex-col md:flex-row gap-4 md:items-center justify-between p-1">
            <div class="relative w-full md:w-80">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchStaff" placeholder="Search by name, position or department..." class="w-full pl-10 pr-4 py-2 bg-white border border-purple-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400 shadow-sm">
            </div>

            <x-filter-button />
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-purple-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-purple-100 bg-purple-50/50">
                        <th class="p-4 cursor-pointer group hover:bg-purple-100 transition-colors">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Staff Member
                                <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                            </div>
                        </th>
                        <th class="p-4 cursor-pointer group hover:bg-purple-100 transition-colors">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department
                                <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                            </div>
                        </th>
                        <th class="p-4 cursor-pointer group hover:bg-purple-100 transition-colors">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position
                                <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                            </div>
                        </th>
                        <th class="p-4 text-right">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-100" id="staffRows">
                    <!-- Rows will be injected by JavaScript -->
                    <tr>
                        <td colspan="4" class="p-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-spinner fa-spin text-3xl text-purple-600 mb-3"></i>
                                <p class="text-gray-500">Loading staff members...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between p-4 border-t border-purple-100 bg-white">
            <p class="text-xs text-gray-500">Showing <span class="font-medium text-gray-900" id="showing-count">0</span> staff members</p>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 disabled:opacity-50 transition-colors" disabled>
                    Previous
                </button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
