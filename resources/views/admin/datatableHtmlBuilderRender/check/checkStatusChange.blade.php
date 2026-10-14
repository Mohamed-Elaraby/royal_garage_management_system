@if (!Auth::user()->hasRole(['general_observer']))
    @php($allCheckStatus = \App\Models\CheckStatus::select('id', 'name') -> get())

    <select id="changeCheckStatus" class="form-control">
        @foreach ($allCheckStatus as $checkStatus)
            <option {{ $checkStatus ->name == 'تم تسليم السيارة الى العميل'? 'disabled': '' }} data-check-id="{{ $query ->id }}" value="{{ $checkStatus -> id }}" {{ $checkStatus -> id == $query-> check_status_id ? 'selected':''}}>{{ $checkStatus -> name }}</option>
        @endforeach
    </select>

@endif
