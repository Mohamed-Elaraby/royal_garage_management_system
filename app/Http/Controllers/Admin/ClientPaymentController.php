<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ClientPaymentDatatable;
use App\Http\Requests\clientPayment\AddAndUpdateClientPaymentRequest;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Client;
use App\Models\ClientPayment;
use App\Models\MoneySafe;
use App\Models\Statement;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientPaymentController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-clientPayments')->only('index');
        $this->middleware('permission:create-clientPayments')->only('create');
        $this->middleware('permission:update-clientPayments')->only('edit');
        $this->middleware('permission:delete-clientPayments')->only('destroy');
    }


    public function index(ClientPaymentDatatable $clientPaymentDatatable)
    {
        return  $clientPaymentDatatable -> render('admin.clientPayments.index');
    }

    public function create(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $clients = Client::pluck('name', 'id')->toArray();
        $branches = Branch::pluck('name', 'id')->toArray();
        return view('admin.clientPayments.create', compact( 'client', 'clients', 'branches'));
    }

    public function store(AddAndUpdateClientPaymentRequest $request)
    {
//dd($request -> all());
        $user_id = Auth::user()->id;
        $amount_paid = $request->amount_paid ?? 0;
        $amount_paid_bank = $request->amount_paid_bank ?? 0;
        $total_amounts_paid = $amount_paid + $amount_paid_bank;
        $receipt_number = ClientPayment::all()->last() ? ClientPayment::all()->last()->id + 1 : 1;

        /* Check if amount paid less than safe money amount redirect to link */
        $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
        $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;

        if ($final_amount_money_safe < $amount_paid){ // on amount in the safe is not enough redirect

            return redirect()->route('admin.clientPayments.index')->with('delete', __('trans.the amount in the safe is not enough'));

        }

        /* Check if amount paid bank less than bank amount redirect to link */
        $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
        $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;

        if ($final_amount_bank < $amount_paid_bank){ // on amount in bank is not enough redirect

            return redirect()->route('admin.clientPayments.index')->with('delete', __('trans.the amount in the bank is not enough'));

        }

        $clientPayment = ClientPayment::create($request->all() + ['receipt_number' => $receipt_number, 'user_id' => $user_id]);
        $payment_id = $clientPayment -> id;

        if ($request -> amount_paid)
        {
            /* Update Money Safe Amount */
            $clientPayment->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe - ($amount_paid)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }

        if ($request -> amount_paid_bank)
        {
            /* Update Bank Amount */
            $clientPayment->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank - ($amount_paid_bank)),
                'money_process_type' => 0,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }

//        /* Update Client balance */
        $client = Client::findOrFail($request->client_id);
        $client_balance_after_subtract_total_amounts_paid = $client->balance - $total_amounts_paid ;
        $client->update(['balance' => ($client->balance - ($total_amounts_paid))]);


        /* Record Transaction On Client Transaction Table */
        $this -> insertToClientTransaction(
            $clientPayment, // relatedModel
            [
                'total_amount'                  => $total_amounts_paid,
                'client_balance'                => $client_balance_after_subtract_total_amounts_paid,
                'details'                       => 'سند صرف رقم / ' . $clientPayment -> receipt_number,
                'amount_paid'                   => $amount_paid,
                'amount_paid_bank'              => $amount_paid_bank,
                'transaction_date'              => $request->payment_date,
                'transaction_type'              => 'credit',
                'credit'                        => $total_amounts_paid,
                'user_id'                       => $user_id,
                'client_id'                     => $request->client_id,
            ]
        );

        /* Record Transaction On Statement Table */

        /* insert record under field custody administration network */
        Statement::create([
            'custody_administration_network'    => $amount_paid_bank,
            'notes'                             => 'عهدة من الادارة',
            'branch_id'                         =>  $request -> branch_id,
        ]);

        $amount_paid = $request->amount_paid ?? null;
        $amount_paid_bank = $request->amount_paid_bank ?? null;
        $this -> insertToStatement(
            $clientPayment, // relatedModel
            [
                'expenses_cash'                 =>  $amount_paid,
                'expenses_network'              =>  $amount_paid_bank,
                'notes'                         =>  ' سند صرف الى العميل ' . $clientPayment -> client -> name,
                'branch_id'                     =>  $request -> branch_id,
            ]
        );
        return redirect()->route('admin.clientPayments.show', $payment_id)->with('success', __('trans.client payment added successfully'));
    }

    public function show($id)
    {
        $payment_data = ClientPayment::findOrFail($id);
        return view('admin.clientPayments.receipt', compact('payment_data'));
    }

}
