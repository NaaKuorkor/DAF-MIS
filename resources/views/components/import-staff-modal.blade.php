{{-- resources/views/components/import-staff-modal.blade.php --}}
<div x-data="{
    modalOpen: false,
    isValid: false,
    fileName: '',
    validate(event) {
        const file = event.target.files[0];
        if(!file) {
            this.isValid = false;
            this.fileName = '';
            return;
        }
        const types = ['csv', 'xlsx', 'xls'];
        const ext = file.name.split('.').pop().toLowerCase();
        this.isValid = types.includes(ext);
        this.fileName = file.name;
    }
}"
@keydown.escape.window="modalOpen = false"
class="relative">
    <button @click="modalOpen = true" class="px-3 py-2 bg-white border border-purple-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-all shadow-sm flex items-center gap-2 group">
        <i class="fas fa-upload text-gray-500 group-hover:text-gray-700"></i>
        Import
    </button>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" @click="modalOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-white">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Import Staff</h3>
                        <p class="text-sm text-gray-500 mt-1">Upload a CSV or Excel file</p>
                    </div>
                    <button @click="modalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('importStaff') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-purple-200 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                            <i class="fas fa-cloud-upload-alt text-4xl text-purple-400 mb-3"></i>
                            <label for="staff-file-upload" class="cursor-pointer">
                                <span class="text-sm font-medium text-purple-600 hover:text-purple-700">Choose a file</span>
                                <span class="text-sm text-gray-500"> or drag and drop</span>
                                <input id="staff-file-upload" type="file" name="file" class="hidden" accept=".csv,.xlsx,.xls" @change="validate" required>
                            </label>
                            <p class="text-xs text-gray-400 mt-2">CSV, XLS, or XLSX (Max 10MB)</p>
                        </div>

                        <div x-show="fileName" class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <i class="fas fa-file-excel text-purple-600"></i>
                            <span class="text-sm text-gray-700 flex-1" x-text="fileName"></span>
                            <i class="fas fa-check-circle text-green-600" x-show="isValid"></i>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-purple-100">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="!isValid" :class="isValid ? 'bg-purple-600 hover:bg-purple-700' : 'bg-gray-300 cursor-not-allowed'" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors shadow-sm">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
