<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend class="font-weight-semibold"><i class="icon-user mr-2"></i> កែប្រែអ្នកប្រើប្រាស់
                    </legend>
                    {{--Profile--}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">រូបភាព:</label>
                        <div class="col-lg-9" id="lfm" data-input="thumbnail" data-preview="holder">
                            <img id="holder" class="rounded-circle shadow" src="{{asset($userEdit->picture)}}" style="margin-top:15px;max-height:100px;">
                            <input id="thumbnail" value="ui/global_assets/images/image.png" type="hidden" name="picture">
                        </div>
                    </div>

                    {{--Name--}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">ឈ្មោះ:</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="{{$userEdit->name}}" placeholder="Type your name" name="name">
                        </div>
                    </div>
                    {{--Gender--}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">ភេទ:</label>
                        <div class="col-lg-9">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input value="ប្រុស" type="radio" class="form-check-input-styled" name="gender" {{$userEdit->gender=='ប្រុស'? 'checked':''}} data-fouc>
                                    ប្រុស
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input value="ស្រី" type="radio" class="form-check-input-styled" name="gender" {{$userEdit->gender=='ស្រី'? 'checked':''}} data-fouc>
                                    ស្រី
                                </label>
                            </div>
                        </div>
                    </div>
                    {{--Email--}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">អ៊ីម៉ែល:</label>
                        <div class="col-lg-9">
                            <input type="email" value="{{$userEdit->email}}" class="form-control" placeholder="Type your email" name="email">
                        </div>
                    </div>
                    {{--Password--}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">លេខសំងាត់:</label>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" placeholder="Type your password" name="password">
                        </div>
                    </div>
                    @if(Auth()->user()->role=='super_admin')
                        {{--Role--}}
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">សិទ្ធិ:</label>
                            <div class="col-lg-9">
                                <select class="form-control form-control-uniform" data-fouc name="role">
                                    <option value="user">Select role</option>
                                    <option value="user" {{$userEdit->role=='user'? 'selected':''}}>User</option>
                                    <option value="admin" {{$userEdit->role=='admin'? 'selected':''}}>Admin</option>
                                    <option value="super_admin" {{$userEdit->role=='super_admin'? 'selected':''}}>Super Admin</option>
                                </select>
                            </div>
                        </div>
                        {{--Status--}}
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Status:</label>
                            <div class="col-lg-9">
                                <select class="form-control form-control-uniform" data-fouc name="status">
                                    <option value="pending">Select status</option>
                                    <option value="active" {{$userEdit->status=='active'? 'selected':''}}>Active</option>
                                    <option value="pending" {{$userEdit->status=='pending'? 'selected':''}}>Pending</option>
                                    <option value="inactive" {{$userEdit->status=='inactive'? 'selected':''}}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    @endif

                </fieldset>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="icon-close2 mr-2"></i>បិទ</button>
        <button type="button" class="btn btn-success" data-id="{{$userEdit->id}}" id="user-update">
            <i class="icon-sync mr-2"></i>
            កែប្រែ
        </button>
    </div>
</form>