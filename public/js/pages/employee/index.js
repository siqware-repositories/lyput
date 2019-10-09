// Setup module
// ------------------------------
var table;
var EmployeeBasic = function() {
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
                url: route('employee.list'),
                method:'post'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name'},
                { data: 'gender', name: 'gender' },
                { data: 'age', name: 'age' },
                { data: 'tel', name: 'tel' },
                { data: 'address', name: 'address' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action',searchable:false,orderable:false },
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0,1,2,3,4,5,6] },
                { className: "text-center", "targets": [ 7 ] },
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
    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDatatable();
            _componentSelect2();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    EmployeeBasic.init();
});
employeeAction();
function employeeAction() {
    /*remove tr*/
    $(document).on('click','#tr-remove',function (){
        $(this.parentNode.parentNode).remove();
    });
    /*employee create*/
    $(document).on('click','#employee-create',function () {
        $.ajax({
            url:route('employee.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store employee*/
    $(document).on('click','#employee-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('employee.store'),
            method:'post',
            data:data,
            success:function (data) {
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
        })
    });
    /*employee edit*/
    $(document).on('click','#employee-edit',function (){
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url:route('employee.edit',id),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store update*/
    $(document).on('click','#employee-update',function () {
        var id = parseInt($(this).attr('data-id'));
        var data = $('#form-edit').serialize();
        $.ajax({
            url:route('employee.update',id),
            method:'put',
            data:data,
            success:function (data) {
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
        })
    });
    /*employee destroy*/
    $(document).on('click','#employee-destroy',function () {
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
                    url:route('employee.destroy',id),
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
    /*create employee salary*/
    $(document).on('click','#employee-salary',function (){
        $.ajax({
            url:route('employee.salary.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store employee salary*/
    $(document).on('click','#employee-salary-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('employee.salary.store'),
            method:'post',
            data:data,
            success:function (data) {
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
        })
    });
    /*employee salary history*/
    $(document).on('click','#employee-salary-history',function () {
        var now =moment().format('Y-MM-DD');
        $.ajax({
            url:route('employee.salary.history'),
            method: 'post',
            data:{'date':now},
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*employee salary history by date*/
    $(document).on('change','#employee',function () {
        var id = parseInt($(this).val());
        $('.datatable-salary-history').DataTable({
            destroy:true,
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('employee.salary.list',id),
                method:'post'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name'},
                { data: 'tel', name: 'tel' },
                { data: 'amount', name: 'amount' },
                { data: 'created_at', name: 'created_at' }
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0,1,2,3,4] },
            ],
            order:[4,'desc']
        });
    });

}
