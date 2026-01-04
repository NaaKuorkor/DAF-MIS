{{-- resources/views/components/filter-button.blade.php --}}
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
        class="absolute right-0 z-50 w-48 mt-2"
        x-cloak>
        <div class="p-1 bg-white border border-purple-200 rounded-lg shadow-lg">
            <button @click="dropdownOpen = false" id="A-Z" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                <i class="fas fa-sort-alpha-down text-gray-400"></i>
                <span>A-Z</span>
            </button>
            <button @click="dropdownOpen = false" id="date" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-purple-50 hover:text-purple-600 transition-colors">
                <i class="fas fa-calendar text-gray-400"></i>
                <span>Date Added</span>
            </button>
        </div>
    </div>
</div>
