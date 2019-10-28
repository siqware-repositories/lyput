<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">គេជំពាក់</h5>
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
                                    @if($value['desc']=='ចំណូលថ្លៃឈ្នួល')
                                        <option value="{{$value->id}}">{{$value->desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </button>
                        <button type="button" class="btn bg-transparent">ទៅកាន់</button>
                        <button type="button" class="btn bg-light p-0">
                            <select name="receiver" class="form-control km">
                                @foreach($receiver as $value)
                                    @if($value['desc']!=='ចំណូលលក់ទំនិញ')
                                        <option value="{{$value->id}}">{{$value->desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2">វិក័យបត្រលក់</label>
            <div class="col-md-10">
                <select name="invoice" class="form-control km">
                    @foreach($invoice as $value)
                        <option value="{{$value->id}}">ID: {{$value->id}} តម្លៃលក់: {{$value->amount}} កាលបរិច្ឆេទ: {{$value->created_at}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2">ពិពណ៌នា</label>
            <div class="col-md-4">
                <input type="text" name="desc" class="form-control" placeholder="ពិពណ៌នា">
            </div>
            <label class="col-md-2">ប្រាក់ជំពាក់</label>
            <div class="col-md-4">
                <input type="number" name="balance" class="form-control" placeholder="ប្រាក់ជំពាក់">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2">ពត៌មានយោង</label>
            <div class="col-md-10">
                <textarea name="memo" class="form-control" rows="5" placeholder="ពត៌មានយោង"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="ar-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>