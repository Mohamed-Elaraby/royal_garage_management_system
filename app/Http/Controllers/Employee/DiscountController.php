<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\DiscountDatatable;
use App\Interfaces\moneySafe\balance;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\Images;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class DiscountController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-discounts')->only('index');
        $this->middleware('permission:create-discounts')->only('create');
        $this->middleware('permission:update-discounts')->only('edit');
        $this->middleware('permission:delete-discounts')->only('destroy');
    }

    public function index(DiscountDatatable $discountDatatable, Request $request)
    {
        $employeeName = '';
        if ($request->employee_id) // Get all check with [ check status id ] from request url
            $employeeName = Employee::findOrFail($request->employee_id)->name; // Get [ check status name ] to send into view
        return $discountDatatable -> render('employee.discounts.index', compact('employeeName'));
    }

    public function create(Request $request)
    {
        $employee_id = $request->employee_id;
        $employee = Employee::findOrFail($employee_id);
        return view('employee.discounts.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $branch_id = Employee::findOrFail($request -> employee_id)->branch_id;
        $amount = $request ->amount;
        $discount = Discount::create($request -> all()+ ['user_id' => $user_id]);
//        $money_safe = new balance();
//        $check_balance = $money_safe -> checkBalance($amount, $branch_id);
//        if ($check_balance)
//        {
//            return redirect() -> route('employee.advances.index') -> with('delete', __('trans.the amount in the safe is not enough'));
//        }else
//        {
//
//            $money_safe -> decreaseBalance($discount, $amount, $branch_id);
//        }


        return redirect() -> route('employee.discount.signature', $discount->id) -> with('success', __('trans.discount added successfully'));
    }

    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return view('employee.discounts.show', compact('discount'));
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('employee.discounts.edit',compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->update($request -> all());
        return redirect() -> route('employee.discounts.index') -> with('success', __('trans.discount edit successfully'));
    }

    public function destroy($id)
    {
        Discount::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.discount delete successfully'));
    }

    public function discount_signature($discount_id)
    {

        $discount = Discount::findOrFail($discount_id);
        return view('employee.discounts.signature', compact('discount'));

    }

    public function signature(Request $request)
    {
        if ($request -> ajax())
        {
            $image_data = $this -> uploadSVGImage($request -> discount_id, $request -> image_data, 'employees_signature', 'discount_signature', $request -> discount_id, 'public');
            // Type => 8 [ 8 For employee discount signature]
            Images::create($image_data+ ['type' => 8]+ ['discount_id' => $request -> discount_id]);
        }
    }

    public function discountReceipt($discount_id)
    {
        $discount = Discount::findOrFail($discount_id);
        $employee_discount_signature = Images::where('discount_id',$discount ->id)->where('type',8) -> latest() -> first();
        return view('employee.discounts.receipt', compact('discount', 'employee_discount_signature'));
    }

    public function discount_receipt_print(Request $request)
    {
//        dd($request->discount_id);
        $discount_id = $request -> discount_id;
//        $data = [];
        $data['discount'] = Discount::findOrFail($discount_id);
        $data['employee_discount_signature'] = Images::where('discount_id',$discount_id)->where('type',8) -> latest() -> first();
        $mpdf = PDF::loadView('employee.discounts.discount_receipt_print', $data, [], [
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
            return $mpdf->download($data['discount']->id.'.pdf');

        }
        return $mpdf->stream($data['discount']->id.'.pdf');
    }
}
