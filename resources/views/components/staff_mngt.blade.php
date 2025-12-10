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
                <button class="rounded-md bg-blue-400 hover:bg-blue-500 p-2">Export</button>
            </div>
        </div>
    </div>

    <div class="flex flex-col ">

        <div class="overflow-x-auto">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-neutral-200/70">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 text-xs font-medium uppercase">Name</th>
                                <th class="px-5 py-3 text-xs font-medium uppercase">Position</th>
                                <th class="px-5 py-3 text-xs font-medium uppercase">Department</th>
                                <th class="px-5 py-3 text-xs font-medium uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200/70" id="staffRows">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
