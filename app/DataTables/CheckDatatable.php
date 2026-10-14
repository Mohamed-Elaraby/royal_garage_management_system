<?php

namespace App\DataTables;


use App\Exports\CheckExport;
use App\Models\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use function Matrix\trace;

class CheckDatatable extends DataTable
{
    protected $data;
//    protected $exportClass = CheckExport::class;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('checkStatus', function ($query){
                return view('admin.datatableHtmlBuilderRender.check.checkStatusChange', compact('query'));
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.check.action', compact('query'));
            })
            ->editColumn('branch', function ($query){
                return $query->branch->display_name ?? '';
            })
            ->editColumn('client_phone', function ($query){
                return $query->client->phone ?? '';
            })
            ->editColumn('client_name', function ($query){
                return $query->client->name ?? '';
            })
            ->editColumn('check_number', function ($query){
                return "<a href='".route('admin.check.receipt', $query->id)."'>".$query -> check_number."</a>";
            })
            ->addColumn('check_saleOrder', function ($query){

                if ($query -> saleOrder) {
                    $id = $query -> saleOrder -> id;
                    if ($query -> saleOrder -> status == 'close') {
                        return "<a style='color: red' href='".route('admin.saleOrders.show', $id)."'>".__('trans.show invoice')."</a>";
                    }elseif ($query -> saleOrder -> status == 'open') {
                        return "<a style='color: green' href='".route('admin.saleOrders.edit', $id)."'>تم اصدار فاتورة مفتوحة</a>";
                    }
                }else
                {
                    return 'لم يتم اصدار الفاتورة';
                }

            })
            ->filterColumn('branch', function($query, $keyword) {
                $query->whereHas('branch', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('client_phone', function($query, $keyword) {
                $query->whereHas('client', function ($q) use ($keyword){
                    $q->where('phone', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('client_name', function($query, $keyword) {
                $query->whereHas('client', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->rawColumns(['checkStatus', 'action', 'check_number', 'check_saleOrder'])
            ->addRowAttr('class', function ($query){

                if (auth()->user()->hasRole(['super_owner', 'owner', 'general_manager', 'deputy_manager'])) {
                    if ($query->management_notes != NULL) {
                        return 'management_notes_select_color'; // add class management_notes to set row background color if management_notes not empty
                    }
                }

                if ($query->checkStatus -> name == 'خرجت بدون اصلاح') {
                    return 'car_status_exit_not_repair_color'; // add class management_notes to set row background color if management_notes not empty
                }

                if ($query->checkStatus -> name == 'تم اصلاح السيارة على الضمان') {
                    return 'car_status_exit_under_warranty_color'; // add class management_notes to set row background color if management_notes not empty
                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param Check $model
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Check $model)
    {
//        $selectQueryColumns = ['id', 'check_number', 'car_type', 'car_color', 'plate_number', 'management_notes', 'check_status_id','branch_id', 'updated_at'];
//        $result = $this -> searchFilter($model, $request, $selectQueryColumns, 'updated_at', [['branch_id','=',Auth::user()->branch_id]] , ['check_status_id' => $request -> check_status_id, 'branch_id' => $request -> branch_id, 'client_id' => $request -> client_id, 'car_exists' => $request -> car_exists]);
//        return $result;

        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59']; // Date Range Used With Search => Array
        $chassis_number = $this -> request() -> get('search_in_chassis_number');
        $client_id = $this -> request() -> get('client_id');
        $branch_id = $this -> request() -> get('branch_id');
        $check_status_id = $this -> request() -> get('check_status_id');
        $car_id = $this -> request() -> get('car_id');
        $car_exists = $this -> request() -> get('car_exists');
        $technical_id = $this -> request() -> get('technical_id');


        $check = $model->newQuery()->checkUserRole()

            // start search in dataTable

            ->where(function ($query)use ($start_date, $end_date, $date_range ){ // filter with date between startDate and endDate
                if ($start_date && $end_date)
                {
                    $query-> whereBetween('created_at' ,$date_range);
                }
            })

//            ->where(function ($query)use ($chassis_number){ // filter with chassis_number
//                if (!empty($chassis_number))
//                {
//                    $query-> where('chassis_number' ,$chassis_number);
//                }
//            })

            // end search in dataTable

            ->where(function ($query)use ($client_id){ // if url contain [ client_id ] get all check of this client
                if (!empty($client_id))
                {
                    $query-> whereHas('car' ,function ($query)use ($client_id){
                        $query -> where('client_id', $client_id);
                    });
                }
            })

            ->where(function ($query)use ($branch_id){ // if url contain [ branch_id ] get all check in branch
                if ($branch_id)
                {
                    $query-> where('branch_id' ,$branch_id);
                }
            })

            ->where(function ($query)use ($check_status_id, $branch_id){ // if url contain [ branch_id and check_status_id] get all check in branch and check_status equal check_status_id
                if ($check_status_id && $branch_id)
                {
                    $query-> where('branch_id' ,$branch_id);
                    $query-> where('check_status_id' ,$check_status_id);
                    $query-> whereMonth('updated_at' ,date('m'));
                    $query-> whereYear('updated_at' ,date('Y'));
                }
            })

            ->where(function ($query)use ($check_status_id){ // if url contain [ branch_id and check_status_id] get all check in branch and check_status equal check_status_id
                if ($check_status_id)
                {
                    $query-> where('check_status_id' ,$check_status_id);
                    $query-> whereMonth('updated_at' ,date('m'));
                    $query-> whereYear('updated_at' ,date('Y'));
                }
            })

            ->where(function ($query)use ($car_exists){ // if url contain [ branch_id and check_status_id] get all check in branch and check_status equal check_status_id
                if ($car_exists == true)
                {
                    $query-> whereHas('checkStatus', function ($checkStatus){
                        $checkStatus->where('name', '!=', 'تم تسليم السيارة الى العميل');
                        $checkStatus->where('name', '!=', 'تم اصلاح السيارة على الضمان');
                        $checkStatus->where('name', '!=', 'خرجت بدون اصلاح');


                    });
                }
            })

            ->where(function ($query)use ($technical_id) { // if url contain [ technical_id ] get all check of technical = technical_id from technicals relation
                if ($technical_id)
                {
                    $query -> whereHas('technicals', function ($query) use ($technical_id){
                        $query-> where('technical_id', $technical_id);
                    });
                }
            });

        if ($car_id) // filter with chassis_number in all branches
        {
            $check = $model->newQuery() -> where('car_id' ,$car_id);
        }
        if (!empty($chassis_number)) // filter with chassis_number in all branches
        {
            $check = $model->newQuery() -> where('chassis_number' ,$chassis_number);
        }
        return $check;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('checkdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
//                    ->addAction([])
                    ->parameters(array_merge($this->getBuilderParameters(),
                        [
                            'initComplete' => "function () {
                                    this.api().columns().every(function () {
                                        var column = this;
                                        var input = document.createElement(\"input\");
                                        $(input).appendTo($(column.footer()).empty())
                                        .on('keyup', function () {
                                            column.search($(this).val(), false, false, true).draw();
                                        });
                                    });
                                }",
                        ]
                    ))
                    ->dom('Blfrtip')
                    ->scrollX(true)
                    ->scrollY(false)
                    ->searching(true)
                    ->responsive(false)
                    ->autoWidth(false)
                    ->lengthMenu([[5,10,25,50,100,-1], [5,10,25,50,100,"الكل"]])
                    ->processing(true)
                    ->serverSide(true)
                    ->language(
                        [
                            "buttons" =>
                                [
                                "export" => 'إستخراج',
                                "print" => 'طباعة',
                                "reset" => 'إعادة ضبط',
                                "reload" => 'إعادة تحميل',
                                ]
                        ]
                    )
                    ->languageUrl('//cdn.datatables.net/plug-ins/1.10.22/i18n/Arabic.json')
                    ->orderBy(10)
                    ->buttons(
                        Button::make('export'),
                        Button::make('print')->formMessage('dffdf'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')     -> title('#') ->searchable(false),
            Column::make('check_number')    -> title( __('trans.check number') )->addClass('no_print_in_datatable'),
            Column::make('check_saleOrder') -> title( __('trans.invoices') )->addClass('no_print_in_datatable'),
            Column::make('car_type')        -> title( __('trans.car type') ),
            Column::make('car_color')       -> title( __('trans.car color') ),
            Column::make('plate_number')    -> title( __('trans.plate number') ),
            Column::make('client_name')     -> title( __('trans.client name') ),
            Column::make('client_phone')    -> title( __('trans.client phone') ),
            Column::make('branch')          -> title( __('trans.branch') ),
            Column::make('checkStatus')     -> title( __('trans.status') )->addClass('no_print_in_datatable'),
            Column::make('updated_at')      -> title( __('trans.last update') )
                ->addClass('date_dir_setting no_print_in_datatable'),
            Column::make('action')          -> title( __('trans.action') )->addClass('no_print_in_datatable')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Check_' . date('YmdHis');
    }

}
