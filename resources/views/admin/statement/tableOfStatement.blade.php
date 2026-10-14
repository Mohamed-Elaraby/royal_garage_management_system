        <div class="model_area">
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title text-center">تعديل تفاصيل الكارت</h4>
                        </div>
                        <div class="modal-body card_details_content"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('trans.close') }}</button>
                            <button type="button" class="btn btn-default" id="update_button" style="display: none" data-dismiss="modal">{{ __('trans.update') }}</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

        <div class="buttons_area">

            <button
                id="statement_print"
                class="btn btn-info btn-sm btn-block"
                data-link="{{ route('admin.statement.print', ['startDate'=>$startDate, 'endDate'=>$endDate, 'branchId'=> $branch->id]) }}"
                href="javascript:void(0);"
                onclick="printJS($(this).data('link'))"
            >
                <span> طباعة اليومية <i class="fa fa-print"></i></span>
            </button>

            <a target="_blank" href="{{ route('admin.statement.print', ['startDate'=>$startDate, 'endDate'=>$endDate, 'branchId'=> $branch->id]) }}"
               class="btn btn-warning btn-sm btn-block">
                <span> عرض اليومية ك PDF <i class="fa fa-paperclip"></i></span>
            </a>

            <a target="_blank" href="{{ route('admin.statement.print', ['startDate'=>$startDate, 'endDate'=>$endDate, 'branchId'=> $branch->id, 'download' => true]) }}"
               class="btn btn-success btn-sm btn-block">
                <span> تحميل اليومية <i class="fa fa-download"></i></span>
            </a>

        </div>
        <table border="1" width="100%" style="font-size: 20px;" class="text-center table_striped_class">

            <colgroup>
                <col>
                <col>
                <col>
            </colgroup>
            <colgroup>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <colgroup>
                <col>
                <col>
            </colgroup>
            <colgroup>
                <col>
                <col>
            </colgroup>
            <col>
            <colgroup>
                <col>
                <col>
            </colgroup>
            <col>
            <thead>
            <tr>
                <th colspan="16">
                    يومية فرع
                        [ {{ $branch -> display_name }} ]
                    من تاريخ
                        [ {{ \Carbon\Carbon::parse($startDate)->dayName . ' ' . $startDate }} ]
                    حتى تاريخ
                        [ {{ \Carbon\Carbon::parse($endDate)->dayName . ' ' . $endDate }} ]
                    <input type="hidden" id="start_date" value="{{ $startDate }}">
                    <input type="hidden" id="end_date" value="{{ $endDate }}">
                    <input type="hidden" id="branch_id" value="{{ $branch -> id }}">
                </th>
            </tr>
            <tr>
                <th colspan="1"></th>
                <th colspan="3">الوارد</th>
                <th colspan="4" class="card_details_title">تفاصيل الكارت</th>
                <th colspan="2">مصروفات</th>
                <th colspan="2">عهدة من الادارة</th>
                <th rowspan="2">نقدى الى الادارة</th>
                <th colspan="2">سلف ورواتب</th>
                <th rowspan="2">البيان</th>
            </tr>
            <tr>
                <td></td>
                <td>كاش</td>
                <td>شبكة</td>
                <td>تحويل</td>

                <td>اجور اليد</td>
                <td>قطع جديدة</td>
                <td>قطع مستعملة</td>
                <td>ضريبة 15%</td>

                <td>كاش</td>
                <td>شبكة</td>

                <td>كاش</td>
                <td>شبكة</td>


                <td>كاش</td>
                <td>شبكة</td>

            </tr>

            </thead>
            <tbody>
                @foreach($statements as $statement)
                    <tr id="{{ $statement -> id }}">
                        <td>
{{--                            {{ dd($statement -> imports_cash, $statement -> imports_network ,$statement -> imports_bank_transfer) }}--}}
                            @if($statement -> card_details_hand_labour || $statement -> card_details_new_parts || $statement -> card_details_used_parts || $statement -> card_details_tax)
                                    <i class="fa fa-edit card_details_edit" data-toggle="modal" data-target="#modal-default"></i>
                            @endif
                        </td>
                        <td class="imports_cash">{{ $statement -> imports_cash }}</td>
                        <td class="imports_network">{{ $statement -> imports_network }}</td>
                        <td class="imports_bank_transfer">{{ $statement -> imports_bank_transfer }}</td>

                        <td class="card_details_item hand_labour_edit">{{ $statement -> card_details_hand_labour }}</td>
                        <td class="card_details_item new_parts_edit">{{ $statement -> card_details_new_parts }}</td>
                        <td class="card_details_item used_parts_edit">{{ $statement -> card_details_used_parts }}</td>
                        <td class="card_details_item tax_edit">{{ $statement -> card_details_tax }}</td>

                        <td>{{ $statement -> expenses_cash }}</td>
                        <td>{{ $statement -> expenses_network }}</td>
                        <td>{{ $statement -> custody_administration_cash }}</td>
                        <td>{{ $statement -> custody_administration_network }}</td>
                        <td>{{ $statement -> cash_to_administration }}</td>
                        <td>{{ $statement -> advances_and_salaries_cash }}</td>
                        <td>{{ $statement -> advances_and_salaries_network }}</td>
                        <td>{{ $statement -> notes }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th></th>
                    <th>{{ $total_imports_cash_formatted }}</th>
                    <th>{{ $total_imports_network_formatted }}</th>
                    <th>{{ $total_imports_bank_transfer_formatted }}</th>
                    <th>{{ $total_card_details_hand_labour_formatted }}</th>
                    <th>{{ $total_card_details_new_parts_formatted }}</th>
                    <th>{{ $total_card_details_used_parts_formatted }}</th>
                    <th>{{ $total_card_details_tax_formatted }}</th>
                    <th>{{ $total_expenses_cash_formatted }}</th>
                    <th>{{ $total_expenses_network_formatted }}</th>
                    <th>{{ $total_custody_administration_cash_formatted }}</th>
                    <th>{{ $total_custody_administration_network_formatted }}</th>
                    <th rowspan="2">{{ $total_cash_to_administration_formatted }}</th>
                    <th>{{ $total_advances_and_salaries_cash_formatted }}</th>
                    <th>{{ $total_advances_and_salaries_network_formatted }}</th>
                    <th rowspan="2">{{ __('trans.total') }}</th>
                </tr>

                <tr>
                    <th colspan="1"></th>
                    <th colspan="3">{{ $total_imports_formatted }}</th>
                    <th colspan="4">{{ $total_card_details_formatted }}</th>
                    <th colspan="2">{{ $total_expenses_formatted }}</th>
                    <th colspan="2">{{ $total_custody_administration_formatted }}</th>
                    <th colspan="2">{{ $total_advances_and_salaries_formatted }}</th>

                </tr>
            </tbody>
        </table>

        <br><br>
        <table border="1" width="50%" style="font-size: 20px; margin: 0 auto" class="text-center table_striped_class">
            <tbody>
            <tr>
                <th colspan="2">
                    يومية فرع
                    [ {{ $branch -> display_name }} ]
                    من تاريخ
                    [ {{ \Carbon\Carbon::parse($startDate)->dayName . ' ' . $startDate }} ]
                    حتى تاريخ
                    [ {{ \Carbon\Carbon::parse($endDate)->dayName . ' ' . $endDate }} ]
                </th>
            </tr>
            <tr>
                <td width="30%">{{ $money_safe_opening_balance_formatted }}</td>
                <td width="70%">رصيد سابق</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_imports_cash_formatted }}</td>
                <td width="70%">اجمالى الوارد</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_custody_administration_formatted }}</td>
                <td width="70%">عهدة من الادارة</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_expenses_formatted }}</td>
                <td width="70%">مصروفات</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_advances_and_salaries_cash_formatted }}</td>
                <td width="70%">سلف ورواتب</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_cash_to_administration_formatted }}</td>
                <td width="70%">نقدى للادارة</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_bank_transfer_and_network_formatted }}</td>
                <td width="70%">بنك تحويل وشبكة</td>
            </tr>
            <tr>
                <td width="30%"> {{ $current_balance_formatted }} </td>
                <td width="70%">الرصيد الحالى</td>
            </tr>

            </tbody>
        </table>
