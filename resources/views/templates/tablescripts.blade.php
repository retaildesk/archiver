@if(isset($dataTable))
    <script>
        $( document ).ready(function() {
            CustomDataTable.setDefaults();
        });

    </script>

    {{$dataTable->scripts()}}

    <script>
        $(document).ready(function (){
            $(".dataTables_wrapper").addClass('startUp');
        });
    </script>
@endif

