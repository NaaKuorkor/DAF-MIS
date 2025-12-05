<div class="flex flex-col p-6">
    <div class='flex flex-col gap-4'>
        <h1 class="font-bold text-4xl">Staff</h1>

        <div class='flex justify-between'>
            <div class='flex justify-start'>
                <input class='border border-gray-400 rounded-lg w-70 p-2 mr-4' type='text' placeholder="Search">
                <x-filter-button />
            </div>
            <div class='flex justify-end space-x-4 ml-2'>
                <x-add-staff-modal />
                <button class="rounded-md bg-blue-400 hover:bg-blue-500 p-2">Export to excelsheet</button>
            </div>
        </div>
    </div>

    <div class="flex flex-col ">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="table" width='100%' id="staff-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data is fetched here using ajax --}}
                        </tbody>
                    </table>
            </div>
            </div>
        </div>
    </div>

</div>
