<div class='flex flex-col p-6'>
    <div class='flex flex-col gap-3'>
        <h1 class='text-4xl font-bold'>Dashboard</h1>
        <p id='welcome-message' class='font-style:italic text-gray-500'>Welcome Dorothy</p>
    </div>
    <div id='cards' class="flex flex-wrap gap-4 min-w-full p-4 h-56">
        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl flex-1 shadow-sm p-6 gap-2 h-full">
                <div class="text-gray-500 text-xl">{{ $card['title'] }}</div>
                <div class="text-3xl font-semibold text-gray-900">{{ $card['value'] }}</div>
            </div>
        @endforeach
    </div>
    <div id="statistics" class="shadow-md rounded-md ">

    </div>

</div>
