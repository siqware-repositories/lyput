@extends('ui.master')
@section('page-title')
    ផ្ទាំងដើម
    @stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">ផ្ទាំងដើម</span></h4>
            </div>
        </div>
    </div>
    @stop
@section('page-content')
    <!-- Basic card -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">ផ្ទាំងដើម</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Members online -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0 retain-earning"></h3>
                            </div>

                            <div>
                                ទឹកប្រាក់សរុប
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div id="members-online"></div>
                        </div>
                    </div>
                    <!-- /members online -->

                </div>
                <div class="col-lg-12">
                    <!-- Members online -->
                    <div class="card">
                        <div class="card-body">
                            {{--btn toollbar--}}
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="btn-toolbar">
                                        <div class="btn-group mt-1">
                                            <button id="btn-today" class="btn btn-light">ថ្ងៃនេះ</button>
                                            <button id="btn-yesterday" class="btn btn-light">ម្សិលមិញ</button>
                                            <button id="btn-last-7days" class="btn btn-light">ប្រាំពីថ្ងៃមុន</button>
                                        </div>
                                        <div class="btn-group mr-2 mt-1">
                                            <button id="btn-last-30days" class="btn btn-light">សាមសិបថ្ងៃមុន</button>
                                            <button id="btn-this-month" class="btn btn-light">ខែនេះ</button>
                                            <button id="btn-last-month" class="btn btn-light">ខែមុន</button>
                                        </div>
                                        <div class="btn-group mt-1">
                                            <button type="button" class="btn btn-light p-0">
                                                <input type="date" id="start" class="form-control border-0">
                                            </button>
                                            <button type="button" class="btn btn-light p-0">
                                                <input type="date" id="end" class="form-control border-0">
                                            </button>
                                            <button type="button" class="btn btn-success" id="btn-range">បង្ហាញ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--end btn toollbar--}}
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <h3 class="font-weight-semibold mb-0 total-sale"></h3>
                                            </div>

                                            <div>
                                                តម្លៃលក់សរុប
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div id="members-online"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <h3 class="font-weight-semibold mb-0 total-pur"></h3>
                                            </div>

                                            <div>
                                                តម្លៃទិញសរុប
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div id="members-online"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <h3 class="font-weight-semibold mb-0 total-remain"></h3>
                                            </div>

                                            <div>
                                                ចំណេញពីការលក់
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div id="members-online"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div id="members-online"></div>
                        </div>
                    </div>
                    <!-- /members online -->

                </div>
            </div>
        </div>
    </div>
    <!-- /basic card -->
    @stop
@push('page-js')
    <script src="{{asset('ui/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/pages/index/index.js')}}"></script>
    @endpush