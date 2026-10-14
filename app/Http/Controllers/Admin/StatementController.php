<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\MoneySafeOpeneingBalance;
use App\Models\Statement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class StatementController extends Controller
{

    protected function format_number ($number)
    {
        return number_format($number, 2);
    }

    public function get_tables_of_statement(Request $request)
    {

//        dd($request -> all());
        if ($request -> ajax())
        {
            $start_date = $request -> startDate;
            $end_date = $request -> endDate;
            $branch_id = $request -> branchId;
            $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];
//            dd($date_range, $branch_id);
//            $branch_id = auth()->user()->branch_id ?? \request('branch_id');
//            $date = $date_range;
            if (!empty($start_date) && !empty($end_date) && !empty($branch_id)){
                $branch = Branch::findOrFail($branch_id);
                $startDate  = $start_date;
                $endDate    = $end_date;

                $statements                                         = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->get();
                $total_imports_cash                                 = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('imports_cash');
                $total_imports_network                              = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('imports_network');
                $total_imports_bank_transfer                        = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('imports_bank_transfer');
                $total_card_details_hand_labour                     = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('card_details_hand_labour');
                $total_card_details_new_parts                       = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('card_details_new_parts');
                $total_card_details_used_parts                      = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('card_details_used_parts');
                $total_card_details_tax                             = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('card_details_tax');
                $total_expenses_cash                                = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('expenses_cash');
                $total_expenses_network                             = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('expenses_network');
                $total_custody_administration_cash                  = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('custody_administration_cash');
                $total_custody_administration_network               = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('custody_administration_network');
                $total_cash_to_administration                       = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('cash_to_administration');
                $total_advances_and_salaries_cash                   = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('advances_and_salaries_cash');
                $total_advances_and_salaries_network                = Statement::where('branch_id', $branch_id) -> whereBetween('created_at', $date_range)->sum('advances_and_salaries_network');

                $total_imports                                      = ($total_imports_cash + $total_imports_network + $total_imports_bank_transfer) ;
                $total_card_details                                 = $total_card_details_hand_labour + $total_card_details_new_parts + $total_card_details_used_parts + $total_card_details_tax ;
                $total_expenses                                     = $total_expenses_cash + $total_expenses_network ;
                $total_custody_administration                       = $total_custody_administration_cash + $total_custody_administration_network ;
                $total_advances_and_salaries                        = $total_advances_and_salaries_cash + $total_advances_and_salaries_network ;
                $money_safe_opening_balance                         = MoneySafeOpeneingBalance::where('branch_id', $branch_id) -> whereDate('updated_at', $start_date)->first('balance') -> balance ?? 0;
                $total_bank_transfer_and_network                    = $total_imports_network + $total_imports_bank_transfer;
                $current_balance                                    = $money_safe_opening_balance + $total_imports_cash - $total_expenses + $total_custody_administration - $total_cash_to_administration - $total_advances_and_salaries;

                $total_imports_formatted                            = $this->format_number($total_imports);
                $total_card_details_formatted                       = $this->format_number($total_card_details);
                $total_expenses_formatted                           = $this->format_number($total_expenses);
                $total_custody_administration_formatted             = $this->format_number($total_custody_administration);
                $total_advances_and_salaries_formatted              = $this->format_number($total_advances_and_salaries);

                $total_imports_cash_formatted                       = $this->format_number($total_imports_cash);
                $total_imports_network_formatted                    = $this->format_number($total_imports_network);
                $total_imports_bank_transfer_formatted              = $this->format_number($total_imports_bank_transfer);
                $total_card_details_hand_labour_formatted           = $this->format_number($total_card_details_hand_labour);
                $total_card_details_new_parts_formatted             = $this->format_number($total_card_details_new_parts);
                $total_card_details_used_parts_formatted            = $this->format_number($total_card_details_used_parts);
                $total_card_details_tax_formatted                   = $this->format_number($total_card_details_tax);
                $total_expenses_cash_formatted                      = $this->format_number($total_expenses_cash);
                $total_expenses_network_formatted                   = $this->format_number($total_expenses_network);
                $total_custody_administration_cash_formatted        = $this->format_number($total_custody_administration_cash);
                $total_custody_administration_network_formatted     = $this->format_number($total_custody_administration_network);
                $total_cash_to_administration_formatted             = $this->format_number($total_cash_to_administration);
                $total_advances_and_salaries_cash_formatted         = $this->format_number($total_advances_and_salaries_cash);
                $total_advances_and_salaries_network_formatted      = $this->format_number($total_advances_and_salaries_network);

                $money_safe_opening_balance_formatted               = $this->format_number($money_safe_opening_balance);
                $total_bank_transfer_and_network_formatted          = $this->format_number($total_bank_transfer_and_network);
                $current_balance_formatted                          = $this->format_number($current_balance);
                return view('admin.statement.tableOfStatement',
                    compact(
                        'branch',
                        'startDate',
                        'endDate',
                        'statements',
                        'total_imports_cash',
                        'total_imports_network',
                        'total_imports_bank_transfer',
                        'total_card_details_hand_labour',
                        'total_card_details_new_parts',
                        'total_card_details_used_parts',
                        'total_card_details_tax',
                        'total_expenses_cash',
                        'total_expenses_network',
                        'total_custody_administration_cash',
                        'total_custody_administration_network',
                        'total_cash_to_administration',
                        'total_advances_and_salaries_cash',
                        'total_advances_and_salaries_network',
                        'total_imports',
                        'total_card_details',
                        'total_expenses',
                        'total_custody_administration',
                        'total_advances_and_salaries',
                        'money_safe_opening_balance_formatted',
                        'total_bank_transfer_and_network_formatted',

                        'total_imports_formatted',
                        'total_card_details_formatted',
                        'total_expenses_formatted',
                        'total_custody_administration_formatted',
                        'total_advances_and_salaries_formatted',

                        'total_imports_cash_formatted',
                        'total_imports_network_formatted',
                        'total_imports_bank_transfer_formatted',
                        'total_card_details_hand_labour_formatted',
                        'total_card_details_new_parts_formatted',
                        'total_card_details_used_parts_formatted',
                        'total_card_details_tax_formatted',
                        'total_expenses_cash_formatted',
                        'total_expenses_network_formatted',
                        'total_custody_administration_cash_formatted',
                        'total_custody_administration_network_formatted',
                        'total_cash_to_administration_formatted',
                        'total_advances_and_salaries_cash_formatted',
                        'total_advances_and_salaries_network_formatted',
                        'current_balance_formatted'
                    ));
            }else
            {
                return '<div class="text-center alert alert-warning">No Data Available .</div>';
            }


//            return view('admin.statement.tableOfStatement');
        }
    }

    public function index()
    {
//        $branch_list = Branch::all(['id', 'display_name']);
        $branch_list = Branch::where(function ($query){
            if (auth()->user()->branch_id != null)
            {
                $query -> where('id', auth()->user()->branch_id);
            }

        })->get(['id', 'display_name']);
        return view('admin.statement.index', compact('branch_list'));
    }

//    public function selectBranch()
//    {
//        $branches = Branch::pluck('name', 'id');
//        return view('admin.statement.selectBranch', compact('branches'));
//    }

    public function statement_print(Request $request)
    {
        $start_date                                         = $request -> startDate;
        $end_date                                           = $request -> endDate;
        $branch_id                                          = $request -> branchId;
        $date_range                                         = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $date                                               = Carbon::today();
        $data['startDate']                                  = $start_date;
        $data['endDate']                                    = $end_date;
        $data['branch']                                     = Branch::findOrFail($branch_id);
        $data['statements']                                 = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->get();
        $data['total_imports_cash']                         = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('imports_cash');
        $data['total_imports_network']                      = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('imports_network');
        $data['total_imports_bank_transfer']                = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('imports_bank_transfer');
        $data['total_card_details_hand_labour']             = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('card_details_hand_labour');
        $data['total_card_details_new_parts']               = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('card_details_new_parts');
        $data['total_card_details_used_parts']              = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('card_details_used_parts');
        $data['total_card_details_tax']                     = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('card_details_tax');
        $data['total_expenses_cash']                        = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('expenses_cash');
        $data['total_expenses_network']                     = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('expenses_network');
        $data['total_custody_administration_cash']          = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('custody_administration_cash');
        $data['total_custody_administration_network']       = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('custody_administration_network');
        $data['total_cash_to_administration']               = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('cash_to_administration');
        $data['total_advances_and_salaries_cash']           = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('advances_and_salaries_cash');
        $data['total_advances_and_salaries_network']        = Statement::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->sum('advances_and_salaries_network');
        $data['total_imports']                              = $data['total_imports_cash'] + $data['total_imports_network'] + $data['total_imports_bank_transfer'] ;
        $data['total_card_details']                         = $data['total_card_details_hand_labour'] + $data['total_card_details_new_parts'] + $data['total_card_details_used_parts'] + $data['total_card_details_tax'] ;
        $data['total_expenses']                             = $data['total_expenses_cash'] + $data['total_expenses_network'] ;
        $data['total_custody_administration']               = $data['total_custody_administration_cash'] + $data['total_custody_administration_network'] ;
        $data['total_advances_and_salaries']                = $data['total_advances_and_salaries_cash'] + $data['total_advances_and_salaries_network'] ;
        $data['moneySafeOpeningBalance']                    = MoneySafeOpeneingBalance::where('branch_id', $branch_id) -> whereBetween('updated_at', $date_range)->first('balance') -> balance ?? 0;

        $mpdf = PDF::loadView('admin.statement.statement_print', $data, [], [
            'margin_top'            => 30,
            'margin_header'         => 10,
            'margin_footer'         => 10,
            'format'                => 'A4-L',
            'orientation'           => 'L'

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;
        if ($request->download)
        {
            return $mpdf->download('E-Statement '.$data['branch']->display_name . ' [ '. $start_date . ' : ' . $end_date . ' ] ' .'.pdf');
        }
        return $mpdf->stream('E-Statement '.$data['branch']->display_name . ' [ '. $start_date . ' : ' . $end_date . ' ] ' .'.pdf');
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Statement $statement)
    {
        //
    }

    public function edit(Statement $statement, Request $request)
    {
//        dd($request->all());
        if ($request -> ajax())
        {
            $id             = $request -> id;
            $hand_labour    = $request -> hand_labour;
            $new_parts      = $request -> new_parts;
            $used_parts     = $request -> used_parts;
            $total_vat      = $request -> total_vat;
            $total_imports  = $request -> total_imports;
            $startDate      = $request -> start_date;
            $endDate        = $request -> end_date;
            $branch_id      = $request -> branch_id;

//            dd($startDate, $endDate, $branch_id);

            return view('admin.statement.card_details_edit', compact(
                'id',
                'hand_labour',
                'new_parts',
                'used_parts',
                'total_imports',
                'total_vat',
                'startDate',
                'endDate',
                'branch_id',

            ));
//            $statement -> update(
//                [
//                    'card_details_hand_labour' => $hand_labour,
//                    'card_details_new_parts' => $new_parts,
//                    'card_details_used_parts' => $used_parts,
//                ]
//            );
        }
    }

    public function update(Request $request, Statement $statement)
    {
        if ($request->ajax())
        {
//            $total_vat = ['total_vat' => $statement->card_details_tax];
            $card_amounts = array_sum($request->except('total_imports'));
//            dd($card_amounts, gettype($card_amounts));
            if ($card_amounts === floatval($request->total_imports)){
                $statement -> update($request->except(['total_imports', 'tax_amount']));
                return response() ->json('updated success', 200);
            }
        }
    }

    public function destroy(Statement $statement)
    {
        //
    }
}
