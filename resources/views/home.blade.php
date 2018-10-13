@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-lg-2" id="sidebar">
            @include('inc.sidebar')
        </div>

        <div class="col-md-10 col-lg-10" id="main">
                <div class="panel-heading" ><span class="glyphicon-home">All</span> </div>
            <div class="row">
                <div class="col-md-2 col-lg-2 col-sm-2 col-lg-offset-">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>All active accounts</h3>
                        </div>
                        <div class="panel-body">
                           <h4>{{$accounts}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-2  col-lg-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>Accounts active today</h3>
                        </div>
                        <div class="panel-body">
                            <h4>{{$today}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-2  col-lg-offset-1 ">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>All active accounts</h3>
                        </div>
                        <div class="panel-body">
                            <h4>{{$accounts}}</h4>
                        </div>
                    </div>                </div>
                <div class="col-md-2 col-lg-2 col-sm-2  col-lg-offset-1 ">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>All active accounts</h3>
                        </div>
                        <div class="panel-body">
                            <h4>{{$accounts}}</h4>
                        </div>
                    </div>                </div>
            </div>
        </div>
    </div>
</div>
@endsection
