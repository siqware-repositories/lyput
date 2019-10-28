@extends('ui.master')
@section('page-title')
    កត់វិក័យបត្រ
@stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">កត់វិក័យបត្រ</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

            <div class="header-elements d-none text-center text-md-left mb-3 mb-md-0">
                {{--<div class="btn-toolbar justify-content-center">
                    <div class="btn-group mr-2">
                        <a href="#" class="btn btn-outline-info"><i class="icon-add mr-2"></i>បន្ថែម-រាយ</a>
                        <a href="#" class="btn btn-outline-info"><i class="icon-add mr-2"></i>បន្ថែម-ដំុ</a>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-info"><i class="icon-add mr-2"></i>បន្ថែម</button>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
@stop
@section('page-content')
    <!-- Basic card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">កត់វិក័យបត្រ</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{--invoice--}}
                    <form class="row" id="invoice-create">
                        @csrf
                        <div class="col-md-4 shadow py-3">
                            <div class="form-group row">
                                <label class="col-md-3">ទំនិញដំុ</label>
                                <div class="col-md-9 input-group">
                                    <select data-placeholder="ជ្រើសរើសទំនិញដំុ" class="form-control form-control-select2-group" data-fouc></select>
                                    <span class="input-group-append" data-toggle="modal" id="playlist-create" data-target="#modal_action">
                                        <button class="btn btn-icon btn-light" type="button"><i class="icon-plus-circle2"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3">ទំនិញរាយ</label>
                                <div class="col-md-9">
                                    <select data-placeholder="ជ្រើសរើសទំនិញរាយ" class="form-control form-control-select2-single" data-fouc></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="btn-toolbar my-3">
                                <div class="btn-group mr-2">
                                    <button type="button" class="btn btn-light p-0">
                                        <select name="sender" class="form-control km">
                                            @foreach($sender as $value)
                                                <option {{$value->type==='assets'?'selected':''}} value="{{$value->id}}">{{$value->desc}}</option>
                                            @endforeach
                                        </select>
                                    </button>
                                    <button type="button" class="btn bg-transparent">ទៅកាន់</button>
                                    <button type="button" class="btn btn-light p-0">
                                        <select name="receiver" class="form-control km">
                                            @foreach($receiver as $value)
                                                <option {{$value->desc==='ចំណូលលក់ទំនិញ'?'selected':''}} value="{{$value->id}}">{{$value->desc}}</option>
                                            @endforeach
                                        </select>
                                    </button>
                                </div>
                            </div>
                            <table id="invoice-list" class="table table-striped table-sm" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ពិពណ៌នា</th>
                                    <th>ចំនួន</th>
                                    <th>តម្លៃលក់</th>
                                    <th>សរុប</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="get-tr">
                                {{--get tr--}}
                                <tr data-id="0">
                                    <input type="hidden" name="invoice_validate" value="ok">
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        ថ្លៃឈ្នួល
                                    </td>
                                    <td>
                                        <input type="number" name="service" step="any" min="0" value="0" class="form-control amount">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        សរុប
                                    </td>
                                    <td>
                                        <input type="number" id="total" readonly step="any" min="0" class="form-control">
                                    </td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="row mt-2">
                                <div class="col-md-12 text-right">
                                    <button id="invoice-store" type="button" class="btn btn-info"><i class="icon-floppy-disk mr-2"></i>រក្សាទុក</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{--/invoice--}}
                </div>
            </div>
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
    <script src="{{asset('ui/global_assets/js/plugins/tables/datatables/extensions/select.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('js/pages/playlist/index.js')}}"></script>
    <script src="{{asset('js/pages/invoice/index.js')}}"></script>
@endpush