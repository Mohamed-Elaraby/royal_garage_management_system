@if (Auth::user()->hasPermission('update-internalTransfer'))
    <select name="status" id="status" class="form-control" {{ $query -> status != 'pending' ? 'disabled' : '' }}>
        <option value="pending" {{ $query -> status == 'pending' ? 'selected' : '' }}>pending</option>
        <option value="accepted" {{ $query -> status == 'accepted' ? 'selected' : '' }}>accepted</option>
        <option value="rejected" {{ $query -> status == 'rejected' ? 'selected' : '' }}>rejected</option>
    </select>
@endif
