@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    {{ csrf_field() }}
                    <a href="login/twitch" class="btn btn-primary">
                                     <span style="margin-right: 10px">
                                       <figure class="tw-svg">
                                       <svg class="tw-svg__asset tw-svg__asset--logoglitch tw-svg__asset--inherit"
                                            width="34px" height="24px" version="1.1" viewBox="0 0 34 24" x="0px"
                                            y="0px">
                                        <path clip-rule="evenodd"
                                              d="M21,9h-2v6h2V9z M5.568,3L4,7v17h5v3h3.886L16,24h5l6-6V3H5.568z M25,16l-4,4h-6l-3,3v-3H8V5h17V16z M16,9h-2v6h2V9z"
                                              fill-rule="evenodd"></path>
                                        </svg>
                                       </figure>
                                    </span>
                        Login with Twitch
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
