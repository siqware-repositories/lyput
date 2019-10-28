<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">បន្ថែមបុគ្គលិក</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-2">ឈ្មោះ</label>
            <div class="col-md-10">
                <input type="text" name="name" class="form-control" placeholder="ឈ្មោះ">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2">ភេទ</label>
            <div class="col-md-4">
                <select class="form-control km" name="gender">
                    <option value="ប្រុស">ជ្រើសរើសភេទ</option>
                    <option value="ប្រុស">ប្រុស</option>
                    <option value="ស្រី">ស្រី</option>
                </select>
            </div>
            <label class="col-md-2">អាយុ</label>
            <div class="col-md-4">
                <input type="number" name="age" class="form-control" placeholder="អាយុ">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2">លេខទូរស័ព្ទ</label>
            <div class="col-md-10">
                <input type="text" name="tel" class="form-control" placeholder="លេខទូរស័ព្ទ">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2">អាស័យដ្ឋាន</label>
            <div class="col-md-10">
                <textarea rows="5" name="address" class="form-control" placeholder="អាស័យដ្ឋាន"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="employee-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>