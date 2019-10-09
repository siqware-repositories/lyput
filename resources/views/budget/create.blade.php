<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">បន្ថែមចំណូល</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="btn-toolbar my-3">
            <div class="btn-group mr-2">
                <button type="button" class="btn bg-transparent">ទៅកាន់</button>
                <button type="button" class="btn btn-light p-0">
                    <select name="receiver" class="form-control km">
                        @foreach($receiver as $value)
                            @if($value['desc']!=='ចំណូលលក់ទំនិញ')
                                <option value="{{$value->id}}">{{$value->desc}} : ({{$value->balance}})</option>
                            @endif
                        @endforeach
                    </select>
                </button>
            </div>
        </div>
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>#</th>
                <th>ពិពណ៌នា</th>
                <th>ទឹកប្រាក់</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="get-more-tr">
            <tr>
                <td class="text-center">1</td>
                <td>
                    <input type="text" placeholder="ពិពណ៌នា" name="income[0][desc]"
                           class="form-control ac-basic ui-autocomplete-input" autocomplete="off">
                </td>
                <td>
                    <input name="income[0][balance]" id="qty" type="number" min="0" step="any"
                           class="form-control" placeholder="ចំនួន">
                </td>
                <td>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <a href="#" id="product-add-row" class="badge badge-info"><i class="icon-add"></i></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="income-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>