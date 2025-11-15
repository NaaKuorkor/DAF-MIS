<div class='flex flex-col items-center p-6'>

    <div id='cards' class="flex flex-wrap gap-4  flex-1">
        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col gap-2">
                <div class="text-gray-500 text-sm">{{ $card['title'] }}</div>
                <div class="text-3xl font-semibold text-gray-900">{{ $card['value'] }}</div>
            </div>
        @endforeach
    </div>
    <div id="statistics" class="shadow-md rounded-md ">

    </div>

</div>
