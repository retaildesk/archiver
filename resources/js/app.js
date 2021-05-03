require('./bootstrap');
require('alpinejs');
var moment = require('moment');
var dropzone = require('dropzone/dist/dropzone');
var app = new function(){
  this.init = function(){
      app.initListners();
  };
  this.initListners = function(){

     $("#generalSearch").keyup(function () {
         var table = CustomDataTable.elem;
         table.search($(this).val());
         table.draw();
    });


      $("#datepicker-range").daterangepicker({
      startDate: moment().startOf("day"),
      endDate: moment().endOf("day"),
      timePicker: true,
      timePickerIncrement: 1,
      timePicker24Hour: true,

      locale: {
          format: 'DD/MM/YYYY HH:mm',
          daysOfWeek: [
              "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za"
          ],
          firstDay: 1,
      }
    });
    $("#datepicker-range").change(function(){
      var table = CustomDataTable.elem;
      if(table !== undefined){
          table.draw();
      }
    });
      $("#datepicker-range2").daterangepicker({
          startDate: moment().subtract('1','year').startOf("year"),
          endDate: moment().subtract('1','year').endOf("year"),
          timePicker: true,
          timePickerIncrement: 1,
          timePicker24Hour: true,

          locale: {
              format: 'DD/MM/YYYY HH:mm',
              daysOfWeek: [
                  "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za"
              ],
              firstDay: 1,
          }
      });
      $("#datepicker-range2").change(function(){
          var table = CustomDataTable.elem;
          if(table !== undefined){
              table.draw();
          }
      });
    $("#status_filter").change(function(){
        var table = CustomDataTable.elem;
        if(table !== undefined){
            table.draw();
        }
    });


  };
};
export  {
    app
};
