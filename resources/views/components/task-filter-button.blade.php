{{-- resources/views/components/task-filter-button.blade.php --}}
<div x-data="{ dropdownOpen: false }" class="relative">
    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 px-3 py-2 bg-white border border-purple-200 rounded-lg shadow-sm whitespace-nowrap hover:bg-purple-50 transition-colors">
        <i class="fas fa-filter text-gray-400"></i>
        <span class="text-sm font-medium text-gray-600">Filter</span>
        <i class="fas fa-chevron-down text-gray-400 text-xs" :class="{ 'rotate-180': dropdownOpen }"></i>
    </button>

    <div x-show="dropdownOpen"
        @click.away="dropdownOpen = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="absolute right-0 z-50 w-56 mt-2"
        x-cloak>
        <div class="p-2 bg-white border border-purple-200 rounded-lg shadow-lg">
            <!-- Priority Filter -->
            <div class="mb-2">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider px-3 py-1.5">Priority</p>
                <button @click="dropdownOpen = false" data-filter="priority" data-value="High" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i class="fas fa-circle text-red-500 text-xs"></i>
                    <span>High</span>
                </button>
                <button @click="dropdownOpen = false" data-filter="priority" data-value="Medium" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i class="fas fa-circle text-amber-500 text-xs"></i>
                    <span>Medium</span>
                </button>
                <button @click="dropdownOpen = false" data-filter="priority" data-value="Low" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i class="fas fa-circle text-green-500 text-xs"></i>
                    <span>Low</span>
                </button>
            </div>
            
            <div class="border-t border-purple-100 my-2"></div>
            
            <!-- Status Filter -->
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider px-3 py-1.5">Status</p>
                <button @click="dropdownOpen = false" data-filter="status" data-value="pending" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i class="fas fa-clock text-amber-500 text-xs"></i>
                    <span>Pending</span>
                </button>
                <button @click="dropdownOpen = false" data-filter="status" data-value="in_progress" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i class="fas fa-spinner text-blue-500 text-xs"></i>
                    <span>In Progress</span>
                </button>
                <button @click="dropdownOpen = false" data-filter="status" data-value="completed" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>Completed</span>
                </button>
            </div>
            
            <div class="border-t border-purple-100 my-2"></div>
            
            <!-- Clear Filter -->
            <button @click="dropdownOpen = false" id="clearTaskFilter" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                <i class="fas fa-times text-gray-400"></i>
                <span>Clear Filters</span>
            </button>
        </div>
    </div>
</div>