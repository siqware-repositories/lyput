// Setup module
// ------------------------------
var table;
var UserBasic = function() {
    //
    // Setup module components
    //
    // Basic Datatable examples
    var _componentDatatable = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [ 5 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
        });
        // Alternative pagination
        $('.datatable-pagination').DataTable({
            pagingType: "simple",
            language: {
                paginate: {'next': $('html').attr('dir') == 'rtl' ? 'Next &larr;' : 'Next &rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Prev' : '&larr; Prev'}
            }
        });

        // Scrollable datatable
        table = $('.datatable-scroll-y').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('user.list'),
                method:'post'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'picture', name: 'picture',searchable:false,orderable:false },
                { data: 'name', name: 'name' },
                { data: 'gender', name: 'gender' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action',searchable:false,orderable:false },
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0,1,2,3,4,5,6,7 ] },
                { className: "text-center", "targets": [ 8 ] },
            ]
        });

        // Resize scrollable table when sidebar width changes
        $('.sidebar-control').on('click', function() {
            table.ajax.reload( null, false );
        });
    };

    // Select2 for length menu styling
    var _componentSelect2 = function() {
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
// filePicker
    var _componentFilePicker = function() {
        $('#lfm').filemanager('file');
    };

    // Uniform
    var _componentUniform = function() {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform({
            fileButtonClass: 'action btn bg-pink-400'
        });
        $('.form-control-uniform').uniform();
        $('.form-check-input-styled').uniform();
    };

    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDatatable();
            _componentSelect2();
        },
        initUniform: function() {
            _componentUniform();
        },
        initFilePicker: function() {
            _componentFilePicker();
        },

    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    UserBasic.init();
    userAction();
});
function userAction() {
    /*user create*/
    $(document).on('click','#user-create',function () {
        $.ajax({
            url:route('user.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                UserBasic.initFilePicker();
                UserBasic.initUniform();
            }
        });
    });
    /*user store*/
    $(document).on('click','#user-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('user.store'),
            method: 'post',
            data:data,
            success:function (data) {
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
                    table.ajax.reload( null, false );
                }else{
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
        });
    });
    /*user destroy*/
    $(document).on('click','#user-destroy',function () {
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
        }).then(function(result) {
            if(result.value) {
                $.ajax({
                    url:route('user.destroy',id),
                    dataType:'json',
                    method:'delete',
                    data:{
                        '_token':$('meta[name="csrf-token"]').attr('content')
                    },
                    success:function (data) {
                        if (data.success) {
                            swal({
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                cancelButtonClass: 'btn btn-light',
                                title: 'រូចរាល់!',
                                text: 'ប្រតិបត្តិការជោគជ័យ!',
                                type: 'success'
                            });
                            table.ajax.reload( null, false );
                        }
                    }
                });
            }
            else if(result.dismiss === swal.DismissReason.cancel) {
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
    /*user create*/
    $(document).on('click','#user-edit',function () {
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url:route('user.edit',id),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                UserBasic.initFilePicker();
                UserBasic.initUniform();
            }
        });
    });
    /*user update*/
    $(document).on('click','#user-update',function () {
        var id = parseInt($(this).attr('data-id'));
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('user.update',id),
            method: 'put',
            data:data,
            success:function (data) {
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    table.ajax.reload( null, false );
                }else{
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
        });
    });
}
