<<div class="flex flex-col p-6">
    <div class='flex flex-col gap-4'>
        <h1 class="font-bold text-4xl">Staff</h1>

        <div class='flex '>
            <input class='border border-gray-400 rounded-lg w-70 p-2 mr-4' type='text' placeholder="Search">
            <x-filter-button />
        </div>
    </div>

    <div class="flex flex-col ">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-neutral-200/70">
                        <thead>
                            <tr class="text-neutral-800">
                               <th class="px-5 py-3 text-xs font-medium text-center uppercase">Name</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Position</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Department</th>
                                <th class="px-5 py-3 text-xs font-medium text-center uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200/70" id="tableRows">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
