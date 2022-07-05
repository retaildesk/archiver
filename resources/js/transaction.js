const axios = require('axios');
var moment = require("moment");
var transaction = new function(){
    this.files = [];
    this.type = "file";

    this.openTransaction = function(transaction_id){
        axios.get('/transaction/'+transaction_id)
            .then(function (response) {
                // handle success
                if(response.data.status == 200) {
                    let data = response.data.transaction;
                    var html = "";
                    html += "<p><b>Datum : </b>" + moment(data.date).format("DD/MM/YYYY") + "</p>";
                    if (data.finished) {
                        var status = "Afgesloten";
                    } else {
                        var status = "Open";
                    }
                    html += "<p><b>Status: </b>" + status + "</p>";
                    html += "<p><b>IBAN : </b>" + data.iban + "</p>";
                    html += "<p><b>Naam : </b><span id='debt_name'>" + data.name + "</span></p>";
                    html += "<p><b>Bedrag : </b>" + parseFloat(data.amount / 100).toFixed(2) + "</p>";
                    html += "<p><b>Omschrijving : </b>" + data.description + "</p>";
                    if(data.comment != ""){
                        html += "<p><b>Notitie : </b>" + data.comment + "</p>";
                    }
                    $("#comment").val(data.comment);

                    $("#transaction_dropzone_id").val(transaction_id);
                    $("#transaction_content").html(html);
                    if (data.finished) {
                        $("#finish_btn").hide();
                        $("#open_btn").show();
                    } else {
                        $("#open_btn").hide();
                        $("#finish_btn").show();
                    }
                    var files = "";
                    for (var i = 0; i < data.files.length; i++) {
                        files += "<div class='block'><a title='"+data.files[i].name+"' href='/upload/file/"+data.files[i].id+"'><b>"+data.files[i].name.substring(0,20)+"</b></a><button onclick= 'deleteFile("+transaction_id+","+data.files[i].id+")' class='bg-white font-semibold py-1 px-1 ml-5 text-xs border border-gray-400 rounded shadow'>Verwijder</button></div>"
                    }
                    transaction.files = data.files;
                    $("#transaction_files").html(files);
                    $(".dz-preview").remove();
                }
            });
    };
    this.getFiles = function(){
        return transaction.files;
    };
    this.saveComment = function(){
        if($("#transaction_dropzone_id").val() > 0){
            axios.post('/transaction/'+$("#transaction_dropzone_id").val()+'/comment',{"comment":$("#comment").val()}).then(function (response){
               transaction.openTransaction($("#transaction_dropzone_id").val());
            });
        }
    };
    this.deleteFile = function(transaction_id,file_id){
        axios.delete('/upload/file/'+file_id).then(function (response){
            openTransaction(transaction_id);
        });
    };
    this.openBrowser = function(type){
        if($("#transaction_dropzone_id").val() > 0){
            transaction.type = type;
            var drp1 = $('#datepicker-range').data('daterangepicker');
            var drp2 = $('#datepicker-range2').data('daterangepicker');
            drp2.setStartDate(drp1.startDate);
            drp2.setEndDate(drp1.endDate);
            $("#browser_search").val($("#debt_name").text());
            $("#transactions_table").hide();
            $("#browser_table").show();
            transaction.applyBrowser();
        }
    };
    this.closeBrowser = function(){
        $("#browser_table").hide();
        $("#transactions_table").show();
    };
    this.applyBrowser = function(){

        let picker = $('#datepicker-range2').data('daterangepicker');
        var date_start = picker.startDate.format('YYYY-MM-DD HH:mm');
        var date_end = picker.endDate.format('YYYY-MM-DD HH:mm');
        var search = $("#browser_search").val();

        axios.post('/browser/'+transaction.type,{"start":date_start,"end":date_end,"q":search}).then(function (response){
            var data = response.data;
            var files = "";
            for (var i = 0; i < data.length; i++) {
                files += "<tr><td>"+moment(data[i].date).format("DD-MM-YY")+"</td><td>"+data[i].from.name+"</td><td>"+data[i].from.email+"</td><td><a href='#' onclick='downloadFile(\""+data[i].url+"\")'>"+data[i].filename+"</a></td><td><button onclick= 'previewFile(\""+data[i].url+"\")' class='bg-white font-semibold py-1 px-1 ml-5 text-xs border border-gray-400 rounded shadow'>Inzien</button><td><button onclick= 'selectFile(\""+data[i].connect+"\")' class='bg-white font-semibold py-1 px-1 ml-5 text-xs border border-gray-400 rounded shadow'>Selecteer</button></td></tr>"
            }
            $("#file_browser_content").html(files);
        });
    };
    this.downloadFile = function(url){
        var win = window.open(url, '_blank');
        win.focus();
    };
    this.selectFile = function(connect){
        if($("#transaction_dropzone_id").val() > 0){
            axios.get('/browser/connect/'+$("#transaction_dropzone_id").val()+'/'+connect).then(function (response){
                transaction.closeBrowser();
                transaction.openTransaction($("#transaction_dropzone_id").val());
            });
        }
    };
    this.previewFile = function(url){
        $(".viewer").css('height',$(window).height());
        $(".viewer").show();
        let html = "<iframe src='"+url+"' width='100%' height='"+$(window).height()  +"px'></iframe>";
        $(".pdf").html(html);
        document.addEventListener('keyup', event => {
            if (event.code === 'Escape') {
                event.preventDefault();
                $("body").css('overflow','auto');
                viewer_opened = false;
                $(".viewer").hide();
            }
        });
    };
    this.transactionStatus = function(status){
        if(confirm("Bjorn?")){
            axios.post('/transaction/'+$("#transaction_dropzone_id").val()+'/status',{"status":status}).then(function (response){
                var table = CustomDataTable.elem;
                if(table !== undefined){
                    table.draw();
                }
            })
        }
    }
};
window.getFiles = transaction.getFiles;
window.openTransaction = transaction.openTransaction;
window.deleteFile = transaction.deleteFile;
window.openBrowser = transaction.openBrowser;
window.closeBrowser = transaction.closeBrowser;
window.applyBrowser = transaction.applyBrowser;
window.downloadFile = transaction.downloadFile;
window.selectFile = transaction.selectFile;
window.previewFile = transaction.previewFile;

window.saveComment = transaction.saveComment;
window.transactionStatus = transaction.transactionStatus;
