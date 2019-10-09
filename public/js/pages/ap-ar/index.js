// Setup module
// ------------------------------
var table;
var _table;
var APARBasic = function () {
    //
    // Setup module components
    //
    // Basic Datatable examples
    var _componentDatatable = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [5]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                    'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                }
            }
        });
        // Alternative pagination
        $('.datatable-pagination').DataTable({
            pagingType: "simple",
            language: {
                paginate: {
                    'next': $('html').attr('dir') == 'rtl' ? 'Next &larr;' : 'Next &rarr;',
                    'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Prev' : '&larr; Prev'
                }
            }
        });

        // Scrollable datatable
        table = $('.datatable-ap').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('ap.list'),
                method: 'post'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'desc', name: 'desc'},
                {data: 'account', name: 'account'},
                {data: 'type', name: 'type'},
                {data: 'invoice', name: 'invoice'},
                {data: 'balance', name: 'balance'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false},
            ],
            "columnDefs": [
                {className: "pl-2", "targets": [0, 1, 2, 3, 4, 5,6]},
                {className: "text-center", "targets": [7]},
            ]
        });
        _table = $('.datatable-ar').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('ar.list'),
                method: 'post'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'desc', name: 'desc'},
                {data: 'account', name: 'account'},
                {data: 'type', name: 'type'},
                {data: 'invoice', name: 'invoice'},
                {data: 'balance', name: 'balance'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false},
            ],
            "columnDefs": [
                {className: "pl-2", "targets": [0, 1, 2, 3, 4, 5,6]},
                {className: "text-center", "targets": [7]},
            ]
        });
        // Resize scrollable table when sidebar width changes
        $('.sidebar-control').on('click', function () {
            table.ajax.reload(null, false);
        });
    };

    // Select2 for length menu styling
    var _componentSelect2 = function () {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });
    };
    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDatatable();
            _componentSelect2();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    APARBasic.init();
    aparBasic();
});
function aparBasic() {
    /*ap create*/
    $(document).on('click', '#ap-create', function () {
        $.ajax({
            url: route('ap.create'),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*ar create*/
    $(document).on('click', '#ar-create', function () {
        $.ajax({
            url: route('ar.create'),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store account payable*/
    $(document).on('click', '#ap-store', function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url: route('ap.store'),
            method: 'post',
            data: data,
            success: function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    document.getElementById('form-create').reset();
                    table.ajax.reload(null, false);
                } else {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*store account payable*/
    $(document).on('click', '#ar-store', function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url: route('ar.store'),
            method: 'post',
            data: data,
            success: function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    document.getElementById('form-create').reset();
                    table.ajax.reload(null, false);
                } else {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*ap invoice show*/
    $(document).on('click', '#ap-invoice-show', function () {
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url: route('ap.invoice.show',id),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*ar invoice show*/
    $(document).on('click', '#ar-invoice-show', function () {
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url: route('ar.invoice.show',id),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*account payable destroy*/
    $(document).on('click', '#ap-destroy', function () {
        var id = parseInt($(this).attr('data-id'));
        swal({
            title: 'តើអ្នកប្រាកដដែរឬទេ?',
            text: "ចុចពាក្យ បាទ/ចាស៎ដើម្បីបន្តរ",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'បាទ/ចាស៎',
            cancelButtonText: 'ទេ',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: route('ap.destroy', id),
                    dataType: 'json',
                    method: 'delete',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                cancelButtonClass: 'btn btn-light',
                                title: 'រូចរាល់!',
                                text: 'ប្រតិបត្តិការជោគជ័យ!',
                                type: 'success'
                            });
                            table.ajax.reload(null, false);
                        }
                    }
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
                swal({
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-light',
                    title: 'មិនរូចរាល់!',
                    text: 'ប្រតិបត្តិការបរាជ័យ!',
                    type: 'error'
                });
            }
        });
    });
    /*ap return create*/
    $(document).on('click', '#ap-return-create', function () {
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url: route('ap.return.create',id),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*ar return create*/
    $(document).on('click', '#ar-return-create', function () {
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url: route('ar.return.create',id),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store account payable return*/
    $(document).on('click', '#ap-return-store', function () {
        var id = parseInt($(this).attr('data-id'));
        var data = $('#form-create').serialize();
        $.ajax({
            url: route('ap.return.store',id),
            method: 'post',
            data: data,
            success: function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    document.getElementById('form-create').reset();
                    table.ajax.reload(null, false);
                } else {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*store account payable return*/
    $(document).on('click', '#ar-return-store', function () {
        var id = parseInt($(this).attr('data-id'));
        var data = $('#form-create').serialize();
        $.ajax({
            url: route('ar.return.store',id),
            method: 'post',
            data: data,
            success: function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    document.getElementById('form-create').reset();
                    _table.ajax.reload(null, false);
                } else {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*calc payable*/
    $(document).on('change keyup keydown keypress blur', '.payable', function (){
        var tr_node = $(this.parentNode.parentNode);
        var el_due = parseFloat(tr_node.find('.due').val());
        tr_node.find('.remain').val(el_due-parseFloat($(this).val()));
    });
}
