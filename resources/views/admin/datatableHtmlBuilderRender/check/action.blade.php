
@php

    if ( $query -> saleOrder && $query -> saleOrder['status'] == 'open') {
        $status_message = 'اصدار فاتورة مبيعات ( مفتوحة )';
    }elseif ( $query -> saleOrder && $query -> saleOrder['status'] == 'close') {
        $status_message = 'اصدار فاتورة مبيعات ( مغلقة )';
    }else {
        $status_message = 'اصدار فاتورة مبيعات';
    }

@endphp

<select name="" id="selectAction" class="form-control">
        <option value="" disabled selected>{{ __('trans.action') }}</option>
        <option value="{{ route('admin.check.show', $query->id) }}">عرض تفاصيل الفحص</option>
        <option value="{{ route('admin.check.images', [$query->id, 1]) }}">عرض صور السيارة</option>
        @if (!Auth::user()->hasRole(['general_observer']))

            <option value="{{ route('admin.check.createDeviceReport', [$query->id, $query->check_number]) }}">رفع تقرير حالة السيارة على الجهاز</option>
        @endif
        <option value="{{ route('admin.check.images', [$query->id, 2]) }}">عرض تقرير حالة السيارة على الجهاز</option>
        @if (!Auth::user()->hasRole(['general_observer']))
            <!-- any check status exists in this array set disabled property on this option -->
            @php($deny_status = ['تم تسليم السيارة الى العميل', 'خرجت بدون اصلاح', 'تم اصلاح السيارة على الضمان'])
            <option {{ in_array($query -> checkStatus -> name, $deny_status)? 'disabled' : '' }} value="{{ route('admin.check.clientSignature', [$query->id, $query->check_number,'exit=true']) }}">تسجيل خروج السيارة من المركز</option>

        @endif
        @if (Auth::user()->hasPermission('read-managementNotes'))
            <option value="{{ route('admin.check.managementNotes', $query->id) }}">ملاحظات الإدارة</option>
        @endif
        <option value="{{ route('admin.check.receipt', $query->id) }}">ايصال استلام السيارة</option>

        @if (Auth::user()->hasPermission('create-saleOrders'))
            <option {{ $query -> saleOrder ? 'disabled': '' }} value="{{ route('admin.saleOrders.create', ['check_id' => $query->id]) }}">{{ $status_message }}</option>
        @endif
    </select>

@if (!Auth::user()->hasRole(['general_observer']))
    @if (Auth::user()->hasRole('branch_manager') && $query->branch_id == Auth::user()->branch_id)
        <a href="{{ route('admin.check.edit', $query->id) }}" class="btn btn-primary">تعديل</a>
    @elseif(!Auth::user()->hasRole('branch_manager') && Auth::user()->hasPermission('update-check'))
        <a href="{{ route('admin.check.edit', $query->id) }}" class="btn btn-primary">تعديل</a>
    @endif

    @if (Auth::user()->hasPermission('delete-check'))
        {!! Form::open(['route' => ['admin.check.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
        <button onclick="return showDeleteMessage()" class="btn btn-danger">حذف</button>
        {!! Form::close() !!}
    @endif
@endif


