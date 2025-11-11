<div x-data="{
        dropdownOpen: false
    }"
    class="relative">

    <button x-on:click="dropdownOpen=true" class="inline-flex justify-center items-center py-2 pr-12 pl-3 h-12 text-sm font-medium bg-white rounded-md border transition-colors text-neutral-700 hover:bg-neutral-100 active:bg-white focus:bg-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
        <img src="https://cdn.devdojo.com/images/may2023/adam.jpeg" class="object-cover w-8 h-8 rounded-full border border-neutral-200" />
        <span class="flex flex-col flex-shrink-0 items-start ml-2 h-full leading-none translate-y-px">
            <span>Dorothy</span>
            <span class="text-xs font-light text-neutral-400">@dorothyamon</span>
        </span>
        <svg class="absolute right-0 mr-3 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
    </button>

    <div x-show="dropdownOpen"
        x-on:click.away="dropdownOpen=false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="-translate-y-2"
        x-transition:enter-end="translate-y-0"
        class="absolute top-0 left-1/2 z-50 mt-12 w-56 -translate-x-1/2"
        x-cloak>
        <div class="p-1 mt-1 bg-white rounded-md border shadow-md border-neutral-200/70 text-neutral-700">
            <div class="px-2 py-1.5 text-sm font-semibold">My Account</div>
            <div class="-mx-1 my-1 h-px bg-neutral-200"></div>
            <a href="#_" class="relative flex cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">

                <span>Profile</span>

            </a>
            <form method='POST' action="{{ route('logout', ['guard' => 'staff']) }}" >
                @csrf
                <button type='submit'  class="relative w-full flex cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">Logout</button>
            </form>
        </div>
    </div>
</div>

