<div x-data="{ modalOpen: false,
    import(){
    return{
        isValid : false,
        validate(event){
            const file = event.target.file[0];
            if(!file){
                this.isValid = false;
                return;
            }

            const types = ['csv', 'xlsx', 'xls'];
    const ext = file.name.split('.').pop().toLowerCase();
            this.isValid = types.includes(ext);
        }
    }
    }
    }"
    @keydown.escape.window="modalOpen = false"
    class="relative z-50 w-auto h-auto">
    <button @click="modalOpen=true" class="rounded-md bg-yellow-400 hover:bg-yellow-500 p-2" >Import</button>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
            <div x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-black/40"></div>
            <div x-show="modalOpen"
                x-trap.inert.noscroll="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative px-7 py-6 w-full bg-white sm:max-w-lg sm:rounded-lg">
                <div class="flex justify-between items-center pb-2">
                    <h3 class="text-lg font-semibold">Import Student Information</h3>
                    <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800  bg-green-400 hover:bg-green-500 p-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="relative w-auto">
                    <form action="{{ route('importStaff')}}" method='POST' enctype="multipart/formdata">
                        <p class="mb-4">Select a file</p>
                        <input type="file" class="block w-full text-sm text-gray-700
           file:mr-4 file:py-2 file:px-4
           file:rounded file:border-0
           file:text-sm file:font-semibold
           file:bg-blue-50 file:text-blue-700
           hover:file:bg-blue-100
           cursor-pointer" accept=".csv,.xlsx,.xls" @change='validate'>
                        <div>
                            <button type="button" @click="modalOpen=false" class="rounded-md bg-red-400 hover:bg-red-500 ">Cancel</button>
                            <button type="submit" :class="isValid ? 'bg-blue-400 : 'bg-grey-300" >Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
