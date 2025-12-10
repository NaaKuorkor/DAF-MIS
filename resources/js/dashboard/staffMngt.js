export default function loadStaff() {
     console.log("staffmngt is loaded");

    const rows = document.getElementById('staffRows');
    const az = document.getElementById('A-Z');
    const date = document.getElementById('date');

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

    function renderTable(staff){
        let html = ""

        staff.forEach(s => {
            //Add a row
            html += `
            <tr class="text-neutral-600 odd:bg-neutral-50 even:bg-white">
                <td class="px-5 py-4 text-sm font-medium whitespace-nowrap">${s.name}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${s.department}</td>
                <td class="px-5 py-4 text-sm whitespace-nowrap">${s.position}</td>
                <td class="px-5 py-4 whitespace-nowrap flex items-center">
                    <button class="bg-blue-600 items-center"><i class="fa-solid fa-pen-to-square m-2" style="color:white"></i></button>
                    <button class="bg-red-600 items-center"><i class="fa-regular fa-trash-can m-2" style="color:white"></i></button>
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


    staffDateFilter();

    az.addEventListener('click', staffAlphabeticFilter);
    date.addEventListener('click', staffDateFilter);


   }
