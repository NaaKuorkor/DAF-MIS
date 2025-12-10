export default function loadStudents() {

    const rows = document.getElementById('tableRows');
    const az = document.getElementById('A-Z');
    const date = document.getElementById('date');

    async function studentsDateFilter(){
        try{
            //Get info from route
            const response = await axios.get('/staff/studentTable/date');
            //Just to check the pagination response
            console.log(response.data.data);
            //Get only the array with the data needed to be injected
            const info = response.data.data;

            //Display within table
            renderTable(info);
        }catch(err){
            console.log('Failed to load student info');
            console.log(err);
            rows.innerHTML = `<p class="text-red-500">Failed to load</p>`;
        }

    }

    function getEdit(student){
        return `<div x-data='{ modalOpen: false, student: ${JSON.stringify(student)},
        submitForm(event) {
        const formData = new FormData(event.target);
        axios.post("/staff/updateStudent", formData)
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
                    <h3 class="text-lg font-semibold">Edit Student Information</h3>
                    <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800  bg-green-400 hover:bg-green-500 p-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="relative w-auto">

                <form method="POST" action='/staff/updateStudent' @submit.prevent="submitForm($event)">
                        <input type='hidden' name='_token'value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="userid" class="text-gray-600">User Id</label>
                                <input type="text" name="userid" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.userid">
                            </div>
                            <div>
                                <label for="studentid" class="text-gray-600">Student Id</label>
                                <input type="text" name="studentid" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.studentid">
                            </div>
                            <div>
                                <label for="fname" class="block text-gray-600">First Name</label>
                                <input type="text" id="fname" name="fname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.fname">
                            </div>
                            <div>
                                <label for="mname" class="block text-gray-600">Middle Name</label>
                                <input type="text" id="mname" name="mname" class="focus:outline-none focus:ring-2 focus:ring-purple-300  border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.mname">
                            </div>
                            <div>
                               <label for="lname" class="block text-gray-600">Surname</label>
                                <input type="text" id="lname" name="lname" required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.lname" >
                            </div>

                            <div>
                                <label for='gender' class="block text-gray-600">Gender</label>
                                <select id="gender" name="gender"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.gender">
                                    <option value="M" >Male</option>
                                    <option value="F" >Female</option>
                                </select>
                            </div>
                            <div>
                                <label for="email" class="block text-gray-600">Email</label>
                                <input type="email" id="email" name="email"  required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.email">
                            </div>
                            <div>
                                 <label for="age" class="block text-gray-600">Age</label>
                                <input type="number" id="age" name="age"  required class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.age">
                            </div>
                            <div>
                                <label for="phone" class="block text-gray-600">Phone</label>
                                <input type="number" id="phone" name="phone" required minlength="10"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.phone">
                            </div>

                            <div>
                                <label for="residence" class="block text-gray-600">Residence</label>
                                <input type="text" id="residence" name="residence"  class="focus:outline-none focus:ring-2 focus:ring-purple-300  focus:invalid:ring-red-500 border border-gray-400 invalid:border-red-500 h-8 p-2 w-full rounded-md" x-model="student.residence">
                            </div>
                            <div>
                                <label for="referral" class="block text-gray-600">Referral</label>
                                <select id='referral' name="referral"  class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.referral">
                                    <option value="Social Media" >Social Media</option>
                                    <option value="Alumni"  >DAF Alumni</option>
                                    <option value="Website"  >Website</option>
                                    <option value="Institution" >Institution</option>
                                    <option value="Other" >Other</option>
                                </select>
                            </div>
                            <div>
                                <label for="employment_status" class="block text-gray-600">Employment</label>
                                <select id="employment_status" name="employment_status" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.employment_status">
                                    <option value="unemployed">Unemployed</option>
                                    <option value="employed">Employed</option>
                                </select>
                            </div>

                            <div>
                            <label for="course" class="block text-gray-600">Course</label>
                                <select id="course" name="course" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.course">
                                    <option value="LS101" >Life Skills</option>
                                </select>
                            </div>
                            <div>
                                <label for="certificate" class="block text-gray-600">Certification</label>
                                <select id="certificate" name="certificate" class="focus:outline-none focus:ring-2 focus:ring-purple-300 border border-gray-400 h-8 p-2 w-full rounded-md" x-model="student.certificate">
                                    <option value="Y" >Yes</option>
                                    <option value="N" >No</option>
                                </select>
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

 function getDelete(student){
        return `<div x-data='{ modalOpen: false,
        student: ${JSON.stringify(student)},
        delete() {
        axios.post("/staff/deleteStudent", {
            userid : this.student.userid
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
                    <h3 class="text-lg font-semibold">Delete Student Information</h3>
                    <button @click="modalOpen=false" class="flex absolute top-0 right-0 justify-center items-center mt-5 mr-5 w-8 h-8 text-gray-600 rounded-full hover:text-gray-800  bg-green-400 hover:bg-green-500 p-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="relative w-auto">
                <div class="items-center pb-8">
                    <p>Are you sure you would like to delete this student's details?</p>
                </div>

                <div class="flex space-x-4 mt-8">
                    <button @click='modalOpen=false' type="button" class="mb-4 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Cancel</button>
                    <button @click="delete()" type="button" class="mb-4 bg-red-600 hover:bg-red-700 rounded-lg text-white font-bold w-50 text-center h-10 shadow">Delete</button>
                </div>
                </div>
            </div>
        </div>
    </template>
</div>`
    }



    function renderTable(students){
        let html = ""

        students.forEach(student => {
            //Add a row
            //And attach full student object to the button
            html += `
            <tr class="text-neutral-600 odd:bg-neutral-50 even:bg-white">
                <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">${student.name}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap text-center">${student.course}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap text-center">${student.cohort}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap text-center">${student.registration_date}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap text-center">${student.referral}</td>
                <td class="px-5 py-4 whitespace-nowrap flex space-x-2 items-center">
                    ${getEdit(student)}
                    ${getDelete(student)}
                </td>
            </tr>`
        });

        rows.innerHTML = html;
    }

    async function studentsAlphabetFilter(){
        try{
            const response = await axios.get('/staff/studentTable/A-Z');

            renderTable(response.data.data);
        }catch(err){
            console.log('Failed to load student info');
            console.log(err);
            rows.innerHTML= `<p class="text-red-500">Failed to load</p>`;
        }
    }




    az.addEventListener('click', studentsAlphabetFilter);
    date.addEventListener('click', studentsDateFilter);

    studentsDateFilter();
}

export function handleStudentSubmit(e){
        e.preventDefault();

        const registerStudent = e.target;
        const formData = new FormData(registerStudent);

        axios.post(registerStudent.action, formData)
        .then(
            response => {
                if(response.data.success) {
                document.querySelector('[x-data]').__x.$data.modalOpen = false;
                }
            }
        ).catch(error => {
            if(error.response && error.response.data.message){
                alert(error.response.data.message);
            } else {
                console.error(error);
                }
        });
    }


