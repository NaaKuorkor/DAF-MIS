console.log('script loaded!!!!');

export default function loadStudents() {
    console.log('DOM loaded');

    const rows = document.getElementById('tableRows');

    async function getStudents(){
        try{
            //Get info from route
            const response = await axios.get('/staff/studentTable');
            //Just to check the pagination response
            console.log(response.data.data);
            //Get only the array with the data needed to be injected
            const info = response.data.data;

            //Display within table
            renderTable(info);
        }catch(err){
            console.log('Failed to load student info');
            console.log(err);
            rows.innerHTML = `<P class="text-red-500">Failed to load</P>`;
        }

    }

    function renderTable(students){
        let html = ""

        students.forEach(student => {
            //Add a row
            html += `
            <tr class="text-neutral-600 odd:bg-neutral-50 even:bg-white">
                <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">${student.name}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${student.course}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${student.cohort}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${student.registration_date}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${student.referral}</td>
                <td class="px-5 py-4 whitespace-nowrap flex">
                    <button class="bg-blue-300 items-center"><i class="fa-solid fa-pen-to-square" style="color:white"></i></button>
                    <button class="bg-red-400 items-center"><i class="fa-regular fa-trash-can" style="color:white"></i></button>
                </td>
            </tr>`
        });

        rows.innerHTML = html;
    }

    getStudents();
}
