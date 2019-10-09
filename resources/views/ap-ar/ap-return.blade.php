<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">សង</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="row my-2">
            <div class="col-md-12">
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <button type="button" class="btn bg-light p-0">
                            <select name="sender" class="form-control km">
                                @foreach($sender as $value)
                                    @if($value['desc']!=='ចំណូលលក់ទំនិញ')
                                        <option value="{{$value->id}}">{{$value->desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </button>
                        <button type="button" class="btn bg-transparent">ទៅកាន់</button>
                        <button type="button" class="btn bg-light p-0">
                            <select name="receiver" class="form-control km">
                                @foreach($receiver as $value)
                                    @if($value['desc']=='ចំណូលលក់ទំនិញ')
                                        <option value="{{$value->id}}">{{$value->desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-sm">
            <thead>
            <tr>
                <th>ត្រូវសង</th>
                <th>សង</th>
                <th>នៅសល់</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input readonly type="number" class="form-control due" value="{{$account_payable->balance}}">
                </td>
                <td>
                    <input type="number" step="any" min="0" name="balance" max="{{$account_payable->balance}}" class="form-control payable" placeholder="សង">
                </td>
                <td>
                    <input type="number" readonly step="any" min="0" class="form-control remain" placeholder="នៅសល់">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" data-id="{{$account_payable->id}}" id="ap-return-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>