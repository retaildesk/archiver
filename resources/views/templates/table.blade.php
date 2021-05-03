<div class="datatable-filters">
    @if($type == "transactions")
        <div class="grid grid-cols-9">
            <div class="col-span-4"><input type="text" class="w-full" id="datepicker-range" data-default="day" autocomplete="off"></div>
            <div class="col-span-3"><input type="text" class="w-full" placeholder="{{'Zoeken...'}}" id="generalSearch"></div>
            <div>
                <select id="status_filter" class="custom_input">
                    <option value="1">Alleen openstaande</option>
                    <option  value="2">Alleen afgesloten</option>
                    <option  value="3">Alles</option>
                </select>
            </div>
        </div>
    @endif
</div>

{{-- is removed on table load --}}

<style id="datatable-init">
    .table thead,
    .dataTables_wrapper thead{
        opacity: 0;
        transition: opacity 0.3s ease-out;
    }

    .dataTables_wrapper.startUp thead{
        opacity: 1 !important;
    }

    .dataTables_wrapper.startUp .dataTables_scrollBody{
        min-height: 150px;
    }

    .dataTables_processing > div{
        border: 1px solid #d3d3d3;
        min-width: 120px;
    }

    .dataTables_processing > div .spinner{
        top: 20px;
        position: absolute;
    }
</style>

<style>
    .dt-top-edit{
        opacity: 0;
        height: 10px;
        position: relative;
        transition: 0.2s opacity ease-in-out;
    }

    .dt-top-edit select,
    .dt-top-edit .bootstrap-select{
        visibility: hidden;
        top: -46px;
        position: absolute;
    }

    .dt-top-edit-open .bootstrap-select{
        visibility: visible;
    }
    .dt-top-edit-open{
        opacity: 1;
    }
</style>

{{$dataTable->table()}}



