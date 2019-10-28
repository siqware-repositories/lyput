<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">ប្រវត្តិប្រាក់ខែ</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="row mb-2">
            <div class="col-md-6">
                <select id="employee" class="form-control km">
                    <option value="1">ជ្រើសរើសបុគ្គលិក</option>
                    @foreach($employee as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table class="table table-striped table-bordered table-sm datatable-salary-history">
            <thead>
            <tr>
                <th class="pl-1">#</th>
                <th class="pl-1">ឈ្មោះ</th>
                <th class="pl-1">លេខទូរស័ព្ទ</th>
                <th class="pl-1">ទឹកប្រាក់</th>
                <th class="pl-1">កាលបរិច្ឆេទ</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="employee-salary-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>