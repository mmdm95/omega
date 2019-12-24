/* ------------------------------------------------------------------------------
*
*  # Advanced datatables
*
*  Specific JS code additions for datatable_advanced.html page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function () {

    // Table setup
    // ------------------------------

    // Setting datatable defaults
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            type: 'numeric-comma', targets: 0,
            orderable: false,
            width: '100px'
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>فیلتر:</span> _INPUT_',
            lengthMenu: '<span>نمایش:</span> _MENU_',
            paginate: {'ضفحه اول': 'صفحه اول', 'صفحه آخر': 'صفحه آخر', 'next': '&larr;', 'previous': '&rarr;'},
            emptyTable: 'موردی یافت نشد.',
            zeroRecords: 'مورد جستجو شده وجود ندارد.',
            info: 'نمایش' + '<span class="text-primary ml-5 mr-5">_START_</span>' + 'تا' +
                '<span class="text-primary ml-5 mr-5">_END_</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-5 mr-5">_TOTAL_</span>' + 'رکورد',
            infoEmpty: 'نمایش' + '<span class="text-primary ml-5 mr-5">0</span>' + 'تا' +
                '<span class="text-primary ml-5 mr-5">0</span>' + 'از' + 'مجموع' + '<span class="text-primary ml-5 mr-5">0</span>' + 'رکورد'
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        },

    });


    // Datatable 'length' options
    $('.datatable-show-all').DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "همه"]]
    });


    // DOM positioning
    $('.datatable-dom-position').DataTable({
        dom: '<"datatable-header length-left"lp><"datatable-scroll"t><"datatable-footer info-right"fi>'
    });


    // Highlighting rows and columns on mouseover
    var lastIdx = null;
    var table = $('.datatable-highlight').DataTable();

    $('.datatable-highlight tbody').on('mouseover', 'td', function () {
        try {
            var colIdx = table.cell(this).index().column;

            if (colIdx !== lastIdx) {
                $(table.cells().nodes()).removeClass('active');
                $(table.column(colIdx).nodes()).addClass('active');
            }
        } catch (ex) {
        }
    }).on('mouseleave', function () {
        $(table.cells().nodes()).removeClass('active');
    });

    table.on('page.dt', function () {
        setTimeout(function () {
            // Lazy loader (pictures, videos, etc.)
            if ($.fn.lazy) {
                $('.lazy').lazy({
                    effect: "fadeIn",
                    effectTime: 800,
                    threshold: 50,
                    // callback
                    afterLoad: function (element) {
                        $(element).css({'background': 'none'});
                    }
                });
            }
        }, 200);
    });

    // Columns rendering
    $('.datatable-columns').dataTable({
        columnDefs: [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                render: function (data, type, row) {
                    return data + ' (' + row[3] + ')';
                },
                targets: 0
            },
            {visible: false, targets: [3]}
        ]
    });


    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder', 'جستجو...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

});
