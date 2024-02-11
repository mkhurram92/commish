
var dataTable;
var dataTableAjaxParams = {};
function refreshdataTable(customArgs, ModuleCallback,fnRowCallback) {
    const table_type = customArgs['type'];
    let table;
    if(table_type == 1){
        // Client
        table = "#TableData";
    }
    else if(table_type==2){
        // Referror
        table = "#referrer_table";
    }else if(table_type==3){
        // Broker
        table = "#broker_table";
    }else{
        table = table_type;
    }
    if (typeof dataTable != 'undefined' && typeof dataTable == 'object' && dataTable instanceof $.fn.dataTable.Api) //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTable.destroy();
    }
    if(jQuery(`${table}`).length <= 0){
        table = "#TableData";
    }
    if (jQuery(`${table}`).length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery(`${table}`).attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery(`${table} thead th`).each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery(`${table} thead th`).each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery(`${table}`).attr('data-allowexport');
        var allowCardView = jQuery(`${table}`).attr('data-allowcardview');
        var dtOrientation = jQuery(`${table}`).attr('data-orientation');
        var dtPageSize = jQuery(`${table}`).attr('data-pagesize');
        var keys = jQuery(`${table}`).attr('data-keys');
        var initcallback = jQuery(`${table}`).attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTable = jQuery(`${table}`).DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery(`${table}`).attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTableAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery(`${table}`).attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTable.columns.adjust();
        }, 100);
    }
}

