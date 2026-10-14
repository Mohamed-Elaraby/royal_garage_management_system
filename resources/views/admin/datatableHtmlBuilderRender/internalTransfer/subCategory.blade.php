@if (Auth::user()->hasPermission('update-internalTransfer'))
<select name="sub_category_id" id="sub_category_id" class="form-control" {{ $query -> status != 'pending' ? 'disabled' : '' }}>
    <option value=""></option>
    @foreach($categories as $category)
        <optgroup label="{{ $category -> name }}">
            @foreach($category -> subCategories as $sub)
                <option value="{{ $sub ->id }}" {{ $sub ->id == $query -> sub_category_id ? 'selected' : '' }}>{{ $sub ->name }}</option>
            @endforeach
        </optgroup>
    @endforeach
</select>
@endif
<span id="sub_category_error" style="display: none"></span>
