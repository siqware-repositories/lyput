<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">បន្ថែមទំនិញ</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="btn-toolbar my-3">
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-light p-0">
                    <select name="sender" class="form-control">
                        @foreach($sender as $value)
                            <option {{$value->type==='owner-equity'?'selected':''}} value="{{$value->id}}">{{$value->desc}}</option>
                        @endforeach
                    </select>
                </button>
                <button type="button" class="btn">ទៅកាន់</button>
                <button type="button" class="btn btn-light p-0">
                    <select name="receiver" class="form-control">
                        @foreach($receiver as $value)
                            <option value="{{$value->id}}">{{$value->desc}}</option>
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
                <th>ចំនួន</th>
                <th>តម្លៃទិញ</th>
                <th>តម្លៃលក់</th>
                <th>សរុប</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="get-more-tr">
            <tr class="bg-success">
                <td class="text-center uncount">0</td>
                <td>
                    <input type="text" placeholder="ពិពណ៌នា" name="desc"
                           class="form-control ac-basic ui-autocomplete-input" autocomplete="off">
                </td>
                <td>
                    <input name="qty" id="qty" type="number" min="0" step="any"
                           class="form-control" placeholder="ចំនួន">
                </td>
                <td>
                    <input name="pur_price" id="purchase" type="number" min="0" step="any"
                           class="form-control" placeholder="តម្លៃទិញ">
                </td>
                <td>
                    <input name="sell_price" id="sell" type="number" min="0" step="any"
                           class="form-control" placeholder="តម្លៃលក់">
                </td>
                <td>
                    <input name="amount" readonly="" id="amount" type="number" min="0"
                           step="any" class="form-control amount" placeholder="សរុប">
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
                <td></td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <a href="#" id="product-add-row" class="badge badge-info"><i class="icon-add"></i></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="product-store-group" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>