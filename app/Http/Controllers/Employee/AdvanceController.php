<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\Advancedatatable;
use App\Interfaces\advance\Make;
use App\Interfaces\advance\Scheduled;
use App\Interfaces\bank\balance as balanceOfBank;
use App\Interfaces\moneySafe\balance;
use App\Interfaces\bank\balance as changeBalanceOfBank;
use App\Interfaces\salary\Calculate;
use App\Models\Advance;
use App\Models\Employee;
use App\Models\Images;
use App\Models\ScheduledAdvance;
use App\Models\Statement;
use App\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class AdvanceController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-advances')->only('index');
        $this->middleware('permission:create-advances')->only('create');
        $this->middleware('permission:update-advances')->only('edit');
        $this->middleware('permission:delete-advances')->only('destroy');
    }

    public function index(Advancedatatable $advancedatatable, Request $request)
    {
        $employeeName = '';
        if ($request->employee_id) // Get all check with [ check status id ] from request url
            $employeeName = Employee::findOrFail($request->employee_id)->name; // Get [ check status name ] to send into view
        return $advancedatatable -> render('employee.advances.index', compact('employeeName'));
    }

    public function create(Request $request)
    {
        $employee_id = $request->employee_id;
        $employee = Employee::findOrFail($employee_id);
        return view('employee.advances.create', compact('employee'));
    }

    public function store(Request $request)
    {
//        dd($request->all());
        $user_id = Auth::user()->id;
        $advance_type = $request->type;
        $branch_id = Employee::findOrFail($request -> employee_id)->branch_id;
        $amount = $request ->amount;
        $payment_method = $request -> payment_method;


        $salary = new Calculate();
        $advance_greater_than_limit = $salary ->check_advance_greater_than_limit($amount, $request -> employee_id);
        if ($advance_greater_than_limit)
            return redirect()->back()->with('details', $advance_greater_than_limit)->withInput()-> with('delete', __('trans.advance alert'));
//            return redirect() -> back() ->withInput()-> with('delete', __('trans.advance alert'));

        if ($advance_type == 'تخصم مباشرة'){

            if ($payment_method === 'cash')
            {
                $money_safe = new balance();
                $check_balance = $money_safe -> checkBalance($amount, $branch_id);
                if ($check_balance)
                {
                    return redirect() -> route('employee.advances.index') -> with('delete', __('trans.the amount in the safe is not enough'));
                }else
                {
                    $advance = Advance::create($request -> all() + ['user_id' => $user_id, 'status' => 'مسددة بالكامل']);
                    $money_safe -> decreaseBalance($advance, $amount, $branch_id);
                    $salary_month = Carbon::now();
                    $salary_month_year = Carbon::parse($salary_month) -> format('Y-m');
                    $this -> insertToStatement(
                        $advance, // relatedModel
                            [
                            'advances_and_salaries_cash'        =>  $amount,
                            'notes'                             =>  'سلفة ' . $advance -> employee -> name . ' شهر ' . $salary_month_year,
                            'branch_id'                         =>  $branch_id,
                            ]
                    );
                }


            }else
            {
                $bank = new balanceOfBank();
                $check_balance = $bank -> checkBalance($amount, $branch_id);

                if ($check_balance)
                {
                    return redirect() -> route('employee.advances.index') -> with('delete', __('trans.the amount in the bank is not enough'));
                }else
                {
                    $advance = Advance::create($request -> all() + ['user_id' => $user_id, 'status' => 'مسددة بالكامل']);
                    $bank -> decreaseBalance($advance, $amount, $branch_id);

                    $salary_month = Carbon::now();
                    $salary_month_year = Carbon::parse($salary_month) -> format('Y-m');
                    /* insert record under field custody administration network */
                    Statement::create([
                        'custody_administration_network'    => $amount,
                        'notes'                             => 'عهدة من الادارة',
                        'branch_id'                         =>  $branch_id,
                    ]);
                    /* Record Transaction On Statement Table */
                    $this -> insertToStatement(
                        $advance, // relatedModel
                        [
                            'advances_and_salaries_network'     =>  $amount,
                            'notes'                             =>  'سلفة ' . $advance -> employee -> name . ' شهر ' . $salary_month_year,
                            'branch_id'                         =>  $branch_id,
                        ]
                    );
                }
            }
        }else
        {


            if ($payment_method === 'cash')
            {
                $money_safe = new balance();
                $check_balance = $money_safe -> checkBalance($amount, $branch_id);
                if ($check_balance)
                {
                    return redirect() -> route('employee.advances.index') -> with('delete', __('trans.the amount in the safe is not enough'));
                }else
                {
                    $advance = Advance::create($request -> except(['single_amount', 'pay_method']) + ['user_id' => $user_id] + ['status' => 'غير مسددة']);
                    $money_safe -> decreaseBalance($advance, $amount, $branch_id);
                }
                $this -> insertToStatement(
                        $advance, // relatedModel
                            [
                            'advances_and_salaries_cash'        =>  $amount,
                            'notes'                             =>  'سلفة ' . $advance -> employee -> name . ' شهر ' . $salary_month_year,
                            'branch_id'                         =>  $branch_id,
                            ]
                    );
            }else
            {
                $bank = new balanceOfBank();
                $check_balance = $bank -> checkBalance($amount, $branch_id);

                if ($check_balance)
                {
                    return redirect() -> route('employee.advances.index') -> with('delete', __('trans.the amount in the bank is not enough'));
                }else
                {
                    $advance = Advance::create($request -> except(['single_amount', 'pay_method']) + ['user_id' => $user_id] + ['status' => 'غير مسددة']);
                    $bank -> decreaseBalance($advance, $amount, $branch_id);
                }
                Statement::create([
                        'custody_administration_network'    => $amount,
                        'notes'                             => 'عهدة من الادارة',
                        'branch_id'                         =>  $branch_id,
                    ]);
                    /* Record Transaction On Statement Table */
                    $this -> insertToStatement(
                        $advance, // relatedModel
                        [
                            'advances_and_salaries_network'     =>  $amount,
                            'notes'                             =>  'سلفة ' . $advance -> employee -> name . ' شهر ' . $salary_month_year,
                            'branch_id'                         =>  $branch_id,
                        ]
                    );
            }


            if ($request -> single_amount)
            {
                $single_amount = $request -> single_amount;
                foreach ($single_amount as $amount) {
                    $sc = new Make();
                    $sc -> createScheduled($amount, $advance -> id, $user_id);
                }
            }
        }

        return redirect() -> route('employee.advance.signature', $advance->id) -> with('success', __('trans.advance added successfully'));
    }

    public function show($id)
    {
        $advance = Advance::findOrFail($id);
        return view('employee.advances.show', compact('advance'));
    }

    public function edit($id)
    {
        $advance = Advance::findOrFail($id);
        return view('employee.advances.edit',compact('advance'));
    }

    public function update(Request $request, $id)
    {
        $advance = Advance::findOrFail($id);
        $advance->update($request -> all());
        return redirect() -> route('employee.advances.index') -> with('success', __('trans.advance edit successfully'));
    }

    public function destroy($id)
    {
        Advance::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.advance delete successfully'));
    }

    public function scheduling_details(Request $request)
    {
        $advance_id = $request->advance_id;
        $scheduled_advances = ScheduledAdvance::where('advance_id', $advance_id)->get();

    }

    public function advance_signature($advance_id)
    {

        $advance = Advance::findOrFail($advance_id);
//        $salary_details = EmployeeSalaryLog::with('employee')->where('employee_id', $advance_id) -> whereMonth('salary_month', $previous_salary_month)-> whereYear('salary_month', $current_salary_year) -> first();
//        return view('employee.advances.receipt', compact('advance'));
        return view('employee.advances.signature', compact('advance'));

    }

    public function signature(Request $request)
    {
        if ($request -> ajax())
        {
            $image_data = $this -> uploadSVGImage($request -> advance_id, $request -> image_data, 'employees_signature', 'advance_signature', $request -> advance_id, 'public');
            // Type => 6 [ 6 For employee advance signature]
            Images::create($image_data+ ['type' => 6]+ ['advance_id' => $request -> advance_id]);
        }
    }

    public function advanceReceipt($advance_id)
    {
        $advance = Advance::findOrFail($advance_id);
        $employee_advance_signature = Images::where('advance_id',$advance ->id)->where('type',6) -> latest() -> first();
        return view('employee.advances.receipt', compact('advance', 'employee_advance_signature'));
    }

    public function advance_receipt_print(Request $request)
    {
//        dd($request->advance_id);
        $advance_id = $request -> advance_id;
//        $data = [];
        $data['advance'] = Advance::findOrFail($advance_id);
        $data['employee_advance_signature'] = Images::where('advance_id',$advance_id)->where('type',6) -> latest() -> first();
        $mpdf = PDF::loadView('employee.advances.advance_receipt_print', $data, [], [
            'margin_top' => 20,
            'margin_header' => 10,
            'margin_footer' => 20,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;
//         $mpdf->download($data['price_list']->chassis_number.'.pdf');
        if ($request->download)
        {
            return $mpdf->download($data['advance']->id.'.pdf');

        }
        return $mpdf->stream($data['advance']->id.'.pdf');
    }
}
