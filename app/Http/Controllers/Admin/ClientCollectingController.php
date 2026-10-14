<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ClientCollectingDatatable;
use App\Http\Requests\clientCollecting\AddAndUpdateClientCollectingRequest;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Client;
use App\Models\ClientCollecting;
use App\Models\MoneySafe;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientCollectingController extends Controller
{
    use HelperTrait ;
    public function __construct()
    {
        $this->middleware('permission:read-clientCollecting')->only('index');
        $this->middleware('permission:create-clientCollecting')->only('create');
        $this->middleware('permission:update-clientCollecting')->only('edit');
        $this->middleware('permission:delete-clientCollecting')->only('destroy');
    }


    public function index(ClientCollectingDatatable $clientCollectingDatatable)
    {
        return  $clientCollectingDatatable -> render('admin.clientCollecting.index');
    }

    public function create(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $clients = Client::pluck('name', 'id')->toArray();
        $branches = Branch::pluck('name', 'id')->toArray();
        return view('admin.clientCollecting.create', compact( 'client', 'clients', 'branches'));
    }

    public function store(AddAndUpdateClientCollectingRequest $request)
    {
//        dd($request->payment_method_bank);
        $user_id = Auth::user()->id;
        $amount_paid = $request->amount_paid ?? 0;
        $amount_paid_bank = $request->amount_paid_bank ?? 0;
        $total_amounts_paid = $amount_paid + $amount_paid_bank;
        $receipt_number = ClientCollecting::all()->last() ? ClientCollecting::all()->last()->id + 1 : 1;

        $clientCollecting = ClientCollecting::create($request->all() + ['receipt_number' => $receipt_number, 'user_id' => $user_id]);
        $collecting_id = $clientCollecting -> id;


        if ($request -> amount_paid)
        {
            /* Update Money Safe Amount */
            $last_amount_money_safe = MoneySafe::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_money_safe = $last_amount_money_safe ? $last_amount_money_safe->final_amount : 0;
            $clientCollecting->moneySafes()->create([
                'amount_paid' => $amount_paid,
                'final_amount' => ($final_amount_money_safe + ($amount_paid)),
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }


        if ($request -> amount_paid_bank)
        {
            /* Check if amount paid bank less than bank amount redirect to link */
            $last_amount_bank = Bank::where('branch_id', $request -> branch_id)->get()->last();
            $final_amount_bank = $last_amount_bank ? $last_amount_bank->final_amount : 0;
            /* Update Bank Amount */
            $clientCollecting->banks()->create([
                'amount_paid' => $amount_paid_bank,
                'final_amount' => ($final_amount_bank + ($amount_paid_bank)),
                'money_process_type' => 1,
                'user_id' => $user_id,
                'branch_id' => $request -> branch_id,
            ]);
        }

        /* Record Transaction On Client Transaction Table */
        $client = Client::findOrFail($request->client_id);
        $client_balance_after_add_total_amounts_paid = $client->balance + $total_amounts_paid ;
        $client->update(['balance' => $client_balance_after_add_total_amounts_paid ]);

        /* Record Transaction On Client Transaction Table */
        $this -> insertToClientTransaction(
            $clientCollecting, // relatedModel
            [
                'total_amount'                  => $total_amounts_paid,
                'client_balance'                => $client_balance_after_add_total_amounts_paid,
                'details'                       => 'سند قبض رقم / ' . $clientCollecting -> receipt_number,
                'amount_paid'                   => $amount_paid,
                'amount_paid_bank'              => $amount_paid_bank,
                'transaction_date'              => $request->collecting_date,
                'transaction_type'              => 'debit',
                'debit'                         => $total_amounts_paid,
                'user_id'                       => $user_id,
                'client_id'                     => $request->client_id,
            ]
        );

            /* Record Transaction On Statement Table */
            $amount_paid = $request->amount_paid ?? null;
            $amount_paid_bank = $request->amount_paid_bank ?? null;
            $amount_paid_network = null;
            $amount_paid_bank_transfer = null;
            if ($request->payment_method_bank)
            {
                $method = $request->payment_method_bank ;
                if ($method == 'شبكة')
                {
                    $amount_paid_network = $amount_paid_bank;
                }
                elseif ($method == 'تحويل بنكى')
                {
                    $amount_paid_bank_transfer = $amount_paid_bank;
                }
                elseif ($method == 'STC-Pay')
                {
                    $amount_paid_network = $amount_paid_bank;
                }

            }
//        dd($amount_paid,$amount_paid_bank,$amount_paid_network,$amount_paid_bank_transfer);
            $this -> insertToStatement(
                $clientCollecting, // relatedModel
                [
                    'imports_cash'                  =>  $amount_paid,
                    'imports_network'               =>  $amount_paid_network,
                    'imports_bank_transfer'         =>  $amount_paid_bank_transfer,
                    'notes'                         =>  ' سند قبض من العميل ' . $clientCollecting -> client -> name,
                    'card_details_hand_labour'      =>  $request -> hand_labour,
                    'card_details_new_parts'        =>  $request -> new_parts,
                    'card_details_used_parts'       =>  $request -> used_parts,
                    'card_details_tax'              =>  0, // VAT removed: never trust client-supplied tax value
                    'branch_id'                     =>  $request -> branch_id,
                ]
            );
        return redirect()->route('admin.clientCollecting.show', $collecting_id)->with('success', __('trans.client collecting added successfully'));
    }

    public function show($id)
    {
        $collecting_data = ClientCollecting::findOrFail($id);
        return view('admin.clientCollecting.receipt', compact('collecting_data'));
    }

}
