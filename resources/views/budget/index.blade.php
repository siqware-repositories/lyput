@extends('ui.master')
@section('page-title')
    ចំណូលចំណាយ
@stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">ចំណូលចំណាយ</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
            <div class="header-elements d-none text-center text-md-left mb-3 mb-md-0">
                <div class="btn-toolbar justify-content-center">
                    <div class="btn-group mr-2">
                        <button data-toggle="modal" id="income-create" data-target="#modal_action" type="button" class="btn btn-outline-info"><i class="icon-add mr-2"></i>ចំណូល</button>
                        <button data-toggle="modal" id="expense-create" data-target="#modal_action" type="button" class="btn btn-outline-info"><i class="icon-minus-circle2 mr-2"></i>ចំណាយ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-content')
    <!-- Basic card -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">បញ្ជីចំណូលចំណាយ</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{--tab--}}
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#left-icon-tab1" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> ចំណូល</a></li>
                <li class="nav-item"><a href="#left-icon-tab2" class="nav-link" data-toggle="tab"><i class="icon-menu7 mr-2"></i> ចំណាយ</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="left-icon-tab1">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped datatable-income table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ពិពណ៌នា</th>
                                    <th>គណនី</th>
                                    <th>ប្រភេទគណនី</th>
                                    <th>ទឹកប្រាក់</th>
                                    <th>កាលបរិច្ឆេទ</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="left-icon-tab2">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped datatable-expense table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ពិពណ៌នា</th>
                                    <th>គណនី</th>
                                    <th>ប្រភេទគណនី</th>
                                    <th>ទឹកប្រាក់</th>
                                    <th>កាលបរិច្ឆេទ</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{--/tab--}}
        </div>
    </div>
    <!-- /basic card -->
    <!-- Action modal -->
    <div id="modal_action" class="modal fade">
        <div class="modal-dialog modal-lg" id="modal-content">
            {{--get data--}}
        </div>
    </div>
    <!-- /Action modal -->
@stop
@push('page-js')
    <script src="{{asset('ui/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('js/pages/budget/index.js')}}"></script>
@endpush