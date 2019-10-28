<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">ប្រាក់ខែបុគ្គលិក</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="btn-toolbar justify-content-end my-3">
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-light p-0">
                    <select name="sender" class="form-control km">
                        @foreach($sender as $value)
                            <option {{$value->desc==='ចំណូលថ្លៃឈ្នួល'?'selected':''}} value="{{$value->id}}">{{$value->desc}}</option>
                        @endforeach
                    </select>
                </button>
                <button type="button" class="btn bg-transparent">ទៅកាន់</button>
                <button type="button" class="btn btn-light p-0">
                    <select name="receiver" class="form-control km">
                        @foreach($receiver as $value)
                            <option value="{{$value->id}}">{{$value->desc}}</option>
                        @endforeach
                    </select>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn bg-transparent p-0 mr-2">
                    កាលបរិច្ឆេទ
                </button>
                <button type="button" class="btn btn-light p-0">
                    <input type="date" class="form-control" name="date">
                </button>
            </div>
        </div>
        <table class="table table-striped table-bordered table-sm">
            <thead>
            <tr>
                <th class="pl-1">#</th>
                <th class="pl-1">ឈ្មោះ</th>
                <th class="pl-1">លេខទូរស័ព្ទ</th>
                <th class="pl-1">ទឹកប្រាក់</th>
                <th class="pl-1"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($employee as $value)
                <tr>
                    <td class="re-number">1</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->tel}}</td>
                    <td>
                        <input type="hidden" name="employee[{{$value->id}}][id]" value="{{$value->id}}">
                        <input type="number" name="employee[{{$value->id}}][salary]" step="any" min="0" class="form-control">
                    </td>
                    <td>
                        <a href="#" id="tr-remove" class="badge badge-warning"><i class="icon-diff-removed"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            
            </tfoot>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="employee-salary-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>