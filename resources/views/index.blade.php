@extends('layouts.app')

@section('page-title', __('Activity Log'))
@section('page-heading', isset($user) ? $user->present()->nameOrEmail : __('Activity Log'))

@section('breadcrumbs')
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    @if (isset($user) && isset($adminView))
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('activity.index') }}">@lang('Activity Log')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->present()->nameOrEmail }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">@lang('Activity Log')</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
@stop

@section('content')
    <div class="card mg-b-10">
    <div class="card-body pd-y-30">
        <form action="" method="GET" id="users-form" class="border-bottom-light mb-3">
            <div class="row justify-content-between mt-3 mb-4">
                <div class="col-lg-5 col-md-6">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search for Action')">

                        <span class="input-group-append">
                            @if (Request::has('search') && Request::get('search') != '')
                                <a href="{{ isset($adminView) ? route('activity.index') : route('profile.activity') }}"
                                   class="btn btn-light d-flex align-items-center"
                                   role="button">
                                    <i class="fas fa-times text-muted"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-activities-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-dashboard mg-b-0">
                <thead>
                    @if (isset($adminView))
                        <th class="min-width-150">@lang('user')</th>
                    @endif
                    <th>@lang('IP Address')</th>
                    <th class="min-width-200">@lang('Message')</th>
                    <th class="min-width-200">@lang('Log Time')</th>
                    <th class="text-center">@lang('More Info')</th>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            @if (isset($adminView))
                                <td>
                                    @if (isset($user))
                                        {{ $activity->user->present()->nameOrEmail }}
                                    @else
                                        <a href="{{ route('activity.user', $activity->ref_user) }}"
                                           data-toggle="tooltip" title="@lang('View Activity Log')">
                                            {{ $activity->user->present()->nameOrEmail }}
                                        </a>
                                    @endif
                                </td>
                            @endif
                            <td>{{ $activity->ip_address }}</td>
                            <td>{{ $activity->activ_desc }}</td>
                            <td>{{ $activity->activ_date->format(config('app.date_time_format')) }}</td>
                            <td class="text-center">
                                <a tabindex="0" role="button" class="btn btn-icon"
                                   data-trigger="focus"
                                   data-placement="left"
                                   data-toggle="popover"
                                   title="@lang('User Agent')"
                                   data-content="{{ $activity->user_agent }}">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $activities->render() !!}
@stop
