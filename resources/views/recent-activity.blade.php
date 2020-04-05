<div class="card mg-b-10">
    <h6 class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        @lang('Latest Activity')

        @if (count($activities))
            <small>
                <a href="{{ route('activity.user', $user->id) }}"
                   class="edit"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="@lang('Complete Activity Log')">
                    @lang('View All')
                </a>
            </small>
        @endif
    </h6>

    <div class="card-body pd-y-30">
        @if (count($activities))
            <table class="table table-dashboard mg-b-0">
                <thead>
                <tr>
                    <th>@lang('Action')</th>
                    <th>@lang('Date')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity->activ_desc }}</td>
                        <td>{{ $activity->activ_date->format(config('app.date_time_format')) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted font-weight-light">
                <em>@lang('No activity from this user yet.')</em>
            </p>
        @endif
    </div>
</div>
