<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\RewardDatatable;
use App\Interfaces\bank\balance as balanceOfBank;
use App\Interfaces\moneySafe\balance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Images;
use App\Models\Reward;
use App\Traits\HelperTrait;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class RewardsController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-rewards')->only('index');
        $this->middleware('permission:create-rewards')->only('create');
        $this->middleware('permission:update-rewards')->only('edit');
        $this->middleware('permission:delete-rewards')->only('destroy');
    }

    public function index(RewardDatatable $rewardDatatable, Request $request)
    {

        $employeeName = '';
        if ($request->employee_id) // Get all check with [ check status id ] from request url
            $employeeName = Employee::findOrFail($request->employee_id)->name; // Get [ check status name ] to send into view
        return $rewardDatatable -> render('employee.rewards.index', compact('employeeName'));
    }

    public function create(Request $request)
    {
        $employee_id = $request->employee_id;
        $employee = Employee::findOrFail($employee_id);
        return view('employee.rewards.create', compact('employee'));
    }

    public function store(Request $request)
    {
//        dd($request->all());

        $user_id = Auth::user()->id;
        $branch_id = Employee::findOrFail($request -> employee_id)->branch_id;
        $amount = $request ->amount;

//        $reward = Reward::create($request -> all() + ['user_id' => $user_id]);
        if ($request->type == 'يحصل عليها العامل فورا')
        {
            $payment_method = $request -> payment_method;

            if ($payment_method === 'cash')
            {
                $money_safe = new balance();
                $check_balance = $money_safe -> checkBalance($amount, $branch_id);
                if ($check_balance)
                {
                   return redirect() -> route('employee.rewards.index') -> with('delete', __('trans.the amount in the safe is not enough'));
                }else
                {
                    $reward = Reward::create($request -> all() + ['user_id' => $user_id, 'status' => 'حصل عليها العامل فورا']);
                    $money_safe -> decreaseBalance($reward, $amount, $branch_id);

                    $this -> insertToStatement(
                        $reward, // relatedModel
                            [
                            'advances_and_salaries_cash'        =>  $amount,
                            'notes'                             =>  $reward -> notes,
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
                    return redirect() -> route('employee.rewards.index') -> with('delete', __('trans.the amount in the bank is not enough'));
                }else
                {
                    $reward = Reward::create($request -> all() + ['user_id' => $user_id, 'status' => 'حصل عليها العامل فورا']);
                    $bank -> decreaseBalance($reward, $amount, $branch_id);

                    $this -> insertToStatement(
                        $reward, // relatedModel
                        [
                            'advances_and_salaries_network'     =>  $amount,
                            'notes'                             =>  $reward -> notes,
                            'branch_id'                         =>  $branch_id,
                        ]
                    );
                }
            }

//            $reward->update(['status' => 'حصل عليها العامل فورا']);
        }else
        {
            $reward = Reward::create($request -> all() + ['user_id' => $user_id, 'payment_method' => 'تضاف الى الراتب']);
        }
//        dd($request -> all());
//        return redirect() -> route('employee.rewards.index') -> with('success', __('trans.reward added successfully'));
        return redirect() -> route('employee.reward.signature', $reward->id) -> with('success', __('trans.reward added successfully'));

    }

    public function show($id)
    {
        $reward = Reward::findOrFail($id);
        return view('employee.rewards.show', compact('reward'));
    }

    public function edit($id)
    {
        $reward = Reward::findOrFail($id);
        return view('employee.rewards.edit',compact('reward'));
    }

    public function update(Request $request, $id)
    {
//        dd($request -> all());
        $reward = Reward::findOrFail($id);
        $reward->update($request -> all());
        return redirect() -> route('employee.rewards.index') -> with('success', __('trans.reward edit successfully'));
    }

    public function destroy($id)
    {
        Reward::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.reward delete successfully'));
    }

    public function reward_signature($reward_id)
    {

        $reward = Reward::findOrFail($reward_id);
        return view('employee.rewards.signature', compact('reward'));

    }

    public function signature(Request $request)
    {
        if ($request -> ajax())
        {
            $image_data = $this -> uploadSVGImage($request -> reward_id, $request -> image_data, 'employees_signature', 'reward_signature', $request -> reward_id, 'public');
            // Type => 7 [ 7 For employee reward signature]
            Images::create($image_data+ ['type' => 7]+ ['reward_id' => $request -> reward_id]);
        }
    }

    public function rewardReceipt($reward_id)
    {
        $reward = Reward::findOrFail($reward_id);
        $employee_reward_signature = Images::where('reward_id',$reward ->id)->where('type',7) -> latest() -> first();
        return view('employee.rewards.receipt', compact('reward', 'employee_reward_signature'));
    }

    public function reward_receipt_print(Request $request)
    {
//        dd($request->reward_id);
        $reward_id = $request -> reward_id;
//        $data = [];
        $data['reward'] = Reward::findOrFail($reward_id);
        $data['employee_reward_signature'] = Images::where('reward_id',$reward_id)->where('type',7) -> latest() -> first();
        $mpdf = PDF::loadView('employee.rewards.reward_receipt_print', $data, [], [
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
            return $mpdf->download($data['reward']->id.'.pdf');

        }
        return $mpdf->stream($data['reward']->id.'.pdf');
    }
}
