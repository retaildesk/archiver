var moment = require('moment');
const CustomDataTable = {
    edit: 0,
    get elem(){
        return LaravelDataTables[Object.keys(LaravelDataTables)[0]];
    },
    getCustomParams(object = {}){
        $(".custom_input").each(function () {
            if ($(this).attr('id'))
                object[$(this).attr('id')] = $(this).val();
        });

        let picker = $('#datepicker-range').data('daterangepicker');
        if (picker !== undefined) {
            object.date_start = picker.startDate.format('YYYY-MM-DD HH:mm');
            object.date_end = picker.endDate.format('YYYY-MM-DD HH:mm');
        }

        if ($(".date-filter").hasClass('active')){
            object.date_start = $(".date-filter.active").attr('data-start_date');
            object.date_end = $(".date-filter.active").attr('data-end_date');
        }
        return object;
    },

    ajax: function (d) {
        return CustomDataTable.getCustomParams(d);
    },

    setDefaults: function () {
        var toolbar = `<'row'B<'col-sm-12'tr>><'d-flex justify-content-between'<'mass_action_toolbar dt-transition-opacity'><'dataTables_info dt-transition-opacity'i><'dataTables_pager text-right dt-transition-opacity'lp>>`;
        $.extend(true, $.fn.DataTable.defaults, {
            dom: toolbar,
            colReorder: {
                enable: false,
                fixedColumnsLeft: 1,
            },
            destroy: true,
            scrollCollapse: true,
            scrollX: true,
            responsive:
               true
            ,
            lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
            processing: true,
            search: false,
            drawCallback: this.drawCallback,
            initComplete: this.initComplete,
            stateSave: true,
            stateSaveParams: function (settings, data) {
                localStorage.DataTables_state_id = CustomDataTable.elem.table().node().id;
                localStorage.DataTables_state_url = window.location.pathname;
                data.custom = CustomDataTable.getCustomParams();
            },
            stateLoadParams: function (settings, data) {
                for (let key in data.custom) {
                    let input = $("#" + key);

                    if (key === 'date_start' && $("#datepicker-range").length >0){
                        $("#datepicker-range").data('daterangepicker').setStartDate(moment(data.custom[key], 'YYYY-MM-DD HH:mm').format('DD/MM/YYYY HH:mm'));
                        continue;
                    }

                    if (key === 'date_end' && $("#datepicker-range").length > 0){
                        $("#datepicker-range").data('daterangepicker').setEndDate(moment(data.custom[key], 'YYYY-MM-DD HH:mm').format('DD/MM/YYYY HH:mm'));
                        continue;
                    }

                    if (input.val() != data.custom[key]) {
                        input.val(data.custom[key]);
                    }
                }
            },
        });
    },

    drawCallback() {
        if (typeof drawCallback === "function") {
            var api = this.api();
            drawCallback(api.rows({page: 'current'}).data());
        }
    },

    initComplete() {
        // let dataTable = CustomDataTable.elem;
        // var state = dataTable.state.loaded();
        // if (state) {
        //     $("#generalSearch").val(state.search.search);
        //
        //     dataTable.columns().eq(0).each(function (colIdx) {
        //         var colSearch = state.columns[colIdx].search;
        //
        //         if (colSearch.search) {
        //             $(`[table-index=${colIdx}]`).val(colSearch.search);
        //         }
        //     });
        // }
        //
        // if (typeof initComplete === "function") {
        //     var api = this.api();
        //     initComplete(api.rows({page: 'current'}).data());
        // }
        //
        // // let btns = dataTable.buttons().container().find(".btn");
        // // if (btns.length > 0){
        // //     btns.removeClass("btn-secondary").prependTo('.subheader__toolbar');
        // // }
        // //
        // // $(".dataTables_wrapper").addClass('initialised');
    }
};
window.CustomDataTable = CustomDataTable;
