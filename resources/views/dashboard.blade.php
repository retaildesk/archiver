<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transacties') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" id="transactions_table">
                    @include('templates.table', ['type' => "transactions"])
                </div>
                <div class="p-6 bg-white border-b border-gray-200" id="browser_table" style="display:none;">
                    <div class="grid grid-cols-3">
                        <div><input type="text" class="w-full" id="datepicker-range2" data-default="day" autocomplete="off"></div>
                        <div><input type="text" class="w-full" placeholder="{{'Zoeken...'}}" id="browser_search"></div>
                        <div>
                            <button class="ml-5 bg-white  font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="browse_apply">
                                Toepassen
                            </button>
                            <button class="bg-red-500  text-white font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="browse_close">
                                Sluiten
                            </button>
                        </div>
                    </div>

                        <div>
                            <table class="file_table mt-10">
                                <thead>
                                <tr>
                                    <th>Datum</th><th>Van</th><th>E-mail</th><th>Filename</th>
                                </tr>
                                </thead>
                                <tbody id="file_browser_content">

                                </tbody>
                            </table>
                        </div>



                </div>
            </div>
            <div class="transaction-view bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" id="transaction_content">
                </div>
                <div class="p-6 bg-white border-b border-gray-200" id="transaction_files">

                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="/upload/transaction" class="dropzone">
                        <input type="hidden" name="transaction_dropzone_id" id="transaction_dropzone_id" value="">
                        {{ csrf_field() }}
                    </form>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <input type="text" value="" id="comment">
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <button class="bg-white  font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="select_file">
                        Bestand selecteren
                    </button>
                    <button class="bg-green-500 text-white font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="finish_btn" style="display:none;">
                        Afsluiten
                    </button>
                    <button class="bg-red-500 text-white font-semibold py-2 px-4 border border-gray-400 rounded shadow" id="open_btn" style="display:none;">
                        Openen
                    </button>
                </div>
            </div>
        </div>
    </div>
    @section("scripts")
        @include('templates.tablescripts');
        <script>
            render = {
                date(number, type, whole_row) {
                    return '<span class="row-id" data-id="' + whole_row.id + '">' + number + '</span>';
                }
            };

            var drawCallback = function(data){
                // if (data[0] !== undefined) {
                //     openTransaction(data[0].id, true);
                // }
                $("#transaction-table tbody").on('click', 'tr', function () {
                    $("tr").removeClass("selected");
                    $(this).addClass("selected");
                    openTransaction($(this).find(".row-id").attr("data-id"));
                });
            };
            $("#select_file").click(function(){
               openBrowser();
            });
            $("#browse_close").click(function(){
               closeBrowser();
            });
            $("#browse_apply").click(function(){
               applyBrowser();
            });
            var comment_timeout = 0;

            $("#comment").on("keyup",function(){
                clearTimeout(comment_timeout);
                comment_timeout = setTimeout(function (){
                    console.log("save comment");
                    saveComment();
                },1000);
            });
            $("#finish_btn").click(function(){
                transactionStatus("close");
            });
            $("#open_btn").click(function(){
                transactionStatus("open");
            });
        </script>
    @endsection

</x-app-layout>

