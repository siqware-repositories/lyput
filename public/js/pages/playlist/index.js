// Setup module
// ------------------------------
var table;
var PlaylistBasic = function() {
    //
    // Return objects assigned to module
    //
    var _searchStock = function () {
        var select2Dom = '.form-control-select2';
        $(select2Dom).select2({
            width:'100%',
            ajax:{
                url:route('search.stock'),
                method:'post',
                dataType:'json',
                delay:250,
                data:function (params) {
                    return {
                        _term: params.term
                    };
                },
            }
        });
        $(select2Dom).on("select2:select", function (e) {
            var id = parseInt($(this).val());
            $.ajax({
                url:route('playlist.item.create',id),
                method: 'post',
                dataType: 'html',
                success:function (data) {
                    var stock_id = [];
                    $('#get-more-tr tr').each(function (key,val) {
                        stock_id.push(parseInt($(val).attr('data-id')))
                    });
                    if (stock_id.indexOf(id)<0) {
                        $('#get-more-tr').append(data);
                    }
                    $('#validate-sub-item').val('0');
                }
            });
        });
    };
    return {
        initSearchStock: function() {
            _searchStock();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    playlistAction();
});
function playlistAction() {
    /*playlist create single*/
    $(document).on('click','#playlist-create',function () {
        $.ajax({
            url:route('playlist.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                PlaylistBasic.initSearchStock();
            }
        });
    });
    /*remove row*/
    $(document).on('click','#playlist-remove-row',function () {
        $(this.parentNode.parentNode).remove();
        var stock_id = [];
        $('#get-more-tr tr').each(function (key,val) {
            stock_id.push(parseInt($(val).attr('data-id')))
        });
        if (stock_id.length===1) {
            $('#validate-sub-item').val('');
        }
    });
    /*store playlist*/
    $(document).on('click','#playlist-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('playlist.store'),
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
                    $('#get-more-tr').html('<tr data-id="0"></tr><input type="hidden" id="validate-sub-item" name="validate">');
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
    /*calc per group unit*/
    $(document).on('change keyup keydown keypress','#form-create .per_group',function (){
       var table_node = $(this.parentNode.parentNode.parentNode.parentNode);
       var tr_node = $(this.parentNode.parentNode);
       var main_qty = parseInt(table_node.find('#main_qty').val());
       var per_group_qty = parseInt(tr_node.find('.per_group').val());
       if (isNaN(per_group_qty)){
           per_group_qty = 0;
       }
       if (isNaN(main_qty)){
            main_qty = 1;
        }
       tr_node.find('.qty').val(main_qty*per_group_qty);
    });
    $(document).on('change keyup keydown keypress','#form-create #main_qty',function (){
        var main_qty = parseInt($(this).val());
        $('#form-create .sub_group').each(function (key,val) {
            var per_group_qty = parseInt($(val).find('.per_group').val());
            if (isNaN(per_group_qty)){
                per_group_qty = 1;
            }
            if (isNaN(main_qty)){
                main_qty = 1;
            }
            $(val).find('.qty').val(main_qty*per_group_qty);
        })
    });
}
