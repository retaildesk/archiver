<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Download') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <select id="year">
                            @foreach($years as $year)
                                <option>{{$year}}</option>
                            @endforeach
                        </select>
                    </div>

                    Bestanden worden klaargezet op ftp-server
                    <button class="ml-5 bg-white  font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="download_btn">
                        Downloaden
                    </button>
                </div>
            </div>
        </div>
    </div>
    @section("scripts")
        <script>
            $("#download_btn").click(function(){
                axios.get('/download/startjob/'+$("#year").val()).then(function (response){
                    alert("Download gestart");
                });
            });
        </script>
    @endsection

</x-app-layout>

