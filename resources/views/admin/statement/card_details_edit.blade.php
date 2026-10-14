
{{--    <div class="row">--}}
{{--        <div class="col-xs-12 text-center">--}}
{{--            <h4>اجمالى الوارد</h4>--}}
{{--            <div id="total_imports_area" class="total_imports_area" style="border: #cfc9c9 1PX solid; padding: 10px">{{ $total_imports }}</div>--}}
{{--        </div>--}}
{{--        <div class="col-xs-3">--}}
{{--            <label for="hand_labour">{{ __('trans.hand labour') }}</label>--}}
{{--            <input type="text" id="hand_labour" class="form-control" value="{{ $hand_labour }}">--}}
{{--        </div>--}}
{{--        <div class="col-xs-3">--}}
{{--            <label for="new_parts">{{ __('trans.new parts') }}</label>--}}
{{--            <input type="text" id="new_parts" class="form-control" value="{{ $new_parts }}">--}}
{{--        </div>--}}
{{--        <div class="col-xs-3">--}}
{{--            <label for="used_parts">{{ __('trans.used parts') }}</label>--}}
{{--            <input type="text" id="used_parts" class="form-control" value="{{ $used_parts }}">--}}
{{--        </div>--}}
{{--        <div class="col-xs-3">--}}
{{--            <label for="total_vat">{{ __('trans.tax amount') }}</label>--}}
{{--            <input type="text" id="total_vat" class="form-control" value="{{ $total_vat }}" readonly>--}}
{{--        </div>--}}
{{--    </div>--}}

    <table id="card_details_edit_table">
        <tr class="text-center">
            <td colspan="4">
                <h4>اجمالى الوارد</h4>
                <div id="total_imports_area" class="total_imports_area" style="border: #cfc9c9 1PX solid; padding: 10px">{{ $total_imports }}</div>
                <input type="hidden" id="start_date" value="{{ $startDate }}">
                <input type="hidden" id="end_date" value="{{ $endDate }}">
                <input type="hidden" id="branch_id" value="{{ $branch_id }}">
            </td>
        </tr>
        <tr>
            <td>
                <input type="hidden" id="statement_id" value="{{ $id }}">
                <label for="hand_labour">{{ __('trans.hand labour') }}</label>
                <input type="text" id="hand_labour" class="form-control" value="{{ $hand_labour }}">
            </td>
            <td>
                <label for="new_parts">{{ __('trans.new parts') }}</label>
                <input type="text" id="new_parts" class="form-control" value="{{ $new_parts }}">
            </td>
            <td>
                <label for="used_parts">{{ __('trans.used parts') }}</label>
                <input type="text" id="used_parts" class="form-control" value="{{ $used_parts }}">
            </td>
            <td>
                <label for="total_vat">{{ __('trans.tax amount') }}</label>
                <input type="text" id="total_vat" class="form-control" value="{{ $total_vat }}" readonly>
            </td>
        </tr>
    </table>

    <div id="card_details_validation_error" style="color: red; display: none"></div>


