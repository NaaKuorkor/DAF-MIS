export default function loadStaff() {
     console.log("staffmngt is loaded");
    axios.defaults.headers.common['X-CSRF-TOKEN'] =
       document.querySelector('meta[name="csrf-token"]').content;

    const rows = document.getElementById('staffRows');
    const az = document.getElementById('A-Z');
    const date = document.getElementById('date');
    const searchStaff = document.getElementById('searchStaff');

    async function staffDateFilter(){
        try{
            //Get info from route
            const response = await axios.get('/staff/staffTable/date');
            //Just to check the pagination response
            console.log(response.data.data);
            //Get only the array with the data needed to be injected
            const info = response.data.data;

            //Display within table
            renderTable(info);
        }catch(err){
            console.log('Failed to load staff info');
            console.log(err);
            rows.innerHTML = `<P class="text-red-500">Failed to load</P>`;
        }

    }

    function getEdit(staff){

        return `<div x-data='{ modalOpen: false, staff: ${JSON.stringify(staff)},
                submitForm(event) {
                const formData = new FormData(event.target);
                axios.post("/staff/updateStaff", formData)
                    .then(response => {
                        if(response.data.success) {
                            this.modalOpen = false;
                            alert(response.data.message);
                        }
                    })
                    .catch(error => {
                        alert(error.response?.data?.message || "Update failed");
                    });
            }
        }'
            @keydown.escape.window="modalOpen = false"
            class="relative z-50 w-auto h-auto">
            <button @click="modalOpen=true"  class="bg-blue-400 p-2 rounded-md items-center"><i class="fa-solid fa-pen-to-square" style="color:white"></i></button>
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
                            <h3 class="text-lg font-semibold">Edit Staff Information</h3>
                            <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800  bg-green-400 hover:bg-green-500 p-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="relative w-auto">

                        <form method="POST" action='/staff/updateStaff' @submit.prevent="submitForm($event)">
                                <input type='hidden' name='_token'value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="userid" class="text-gray-600">User Id</label>
                                        <input type="text" name="userid" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.userid">
                                    </div>
                                    <div>
                                        <label for="staffid" class="text-gray-600">Staff Id</label>
                                        <input type="text" name="studentid" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.staffid">
                                    </div>
                                    <div>
                                        <label for="fname" class="block text-gray-600">First Name</label>
                                        <input type="text" id="fname" name="fname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.fname">
                                    </div>
                                    <div>
                                        <label for="mname" class="block text-gray-600">Middle Name</label>
                                        <input type="text" id="mname" name="mname" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.mname">
                                    </div>
                                    <div>
                                       <label for="lname" class="block text-gray-600">Surname</label>
                                        <input type="text" id="lname" name="lname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.lname" >
                                    </div>

                                    <div>
                                        <label for='gender' class="block text-gray-600">Gender</label>
                                        <select id="gender" name="gender"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.gender">
                                            <option value="M" >Male</option>
                                            <option value="F" >Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-gray-600">Email</label>
                                        <input type="email" id="email" name="email"  required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.email">
                                    </div>
                                    <div>
                                         <label for="age" class="block text-gray-600">Age</label>
                                        <input type="number" id="age" name="age"  required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.age">
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-gray-600">Phone</label>
                                        <input type="number" id="phone" name="phone" required minlength="10"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.phone">
                                    </div>

                                    <div>
                                        <label for="residence" class="block text-gray-600">Residence</label>
                                        <input type="text" id="residence" name="residence" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  focus:invalid:ring-red-500 border border-gray-400 focus:invalid:border-red-500 h-8 p-2 w-full rounded-md" x-model="staff.residence">
                                    </div>

                                    <div>
                                        <label for="position" class="block text-gray-600">Position</label>
                                        <input type="text" id="position" name="position"  class="focus:outline-none focus:ring-2 focus:ring-purple-300  focus:invalid:ring-red-500 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.position">
                                    </div>

                                    <div>
                                        <label for="department" class="block text-gray-600">Department</label>
                                        <input type="text" id="department" name="department" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  focus:invalid:ring-red-500 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="staff.department">
                                    </div>



                                </div>

                                <div class="flex mt-4 space-x-4">
                                    <button type="submit" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Save</button>
                                    <button @click.prevent="modalOpen=false" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Cancel</button>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>
            </template>
        </div>`
    }



    function getDelete(staff){

         return `<div x-data='{ modalOpen: false,
                staff: ${JSON.stringify(staff)},
                delete(event) {
                axios.post("/staff/deleteStaff", {
                    userid : this.staff.userid
                    })
                    .then(response => {
                        if(response.data.success) {
                            this.modalOpen = false;
                            alert(response.data.message);
                        }
                    })
                    .catch(error => {
                        alert(error.response?.data?.message || "Deletion failed");
                    });
            }
        }'
            @keydown.escape.window="modalOpen = false"
            class="relative z-50 w-auto h-auto">
            <button @click="modalOpen=true" class="bg-red-600 p-2 rounded-md items-center"><i class="fa-regular fa-trash-can" style="color:white"></i></button>
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
                            <h3 class="text-lg font-semibold">Delete Staff Information</h3>
                            <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800  bg-green-400 hover:bg-green-500 p-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="relative w-auto">
                        <div class="items-center pb-8">
                            <p>Are you sure you would like to delete this staff's details?</p>
                        </div>
                        <form action='/staff/deleteStaff' method='POST' @submit.prevent="delete($event)">
                             <input type='hidden' name='_token'value="${document.querySelector('meta[name="csrf-token"]').content}">

                            <div class="flex space-x-4 mt-8">
                                <button @click='modalOpen=false' type="button" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Cancel</button>
                                <button type="submit" class="mb-4 bg-red-600 hover:bg-red-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Delete</button>
                            </div>
                        </form>
                        </div>

                    </div>
                </div>
            </template>
        </div>`
    }

    function renderTable(staff){
        let html = ""

        if(staff.length === 0){
            html = `
            <tr>
                <td colspan="6" class="px-5 py-4 text-center text-neutral-500">
                    No staff found.
                </td>
            </tr>
            `;
            rows.innerHTML = html;
            return;
        }

        staff.forEach(s => {
            //Add a row
            html += `
            <tr class="text-neutral-600 odd:bg-neutral-50 even:bg-white">
                <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">${s.name}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${s.department}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${s.position}</td>
                <td class="px-5 py-4 whitespace-nowrap flex space-x-2 items-center">
                    ${getEdit(s)}
                    ${getDelete(s)}
                </td>
            </tr>`
        });

        rows.innerHTML = html;
    }

    async function staffAlphabeticFilter(){
        try{
            console.log('Filtering alphabetically')
            const response = await axios.get('/staff/staffTable/A-Z');

            renderTable(response.data.data);
        }catch(err){
            console.log('failed to load staff info');
            console.log(err);
            rows.innerHTML = `<P class="text-red-500">Failed to load</P>`;
        }
    }

    async function search(){
        const query = this.value;
        try{
            const response = await axios.get('/staff/searchStaff?q=' + query);
            const students = response.data.data;

                renderTable(staff);
        }catch(err){
            console.log('Search failed: ', err);
        }
    }



    staffDateFilter();

    searchStaff.addEventListener('input', search);
    az.addEventListener('click', staffAlphabeticFilter);
    date.addEventListener('click', staffDateFilter);


}


