<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ClientDatatable;
use App\DataTables\ClientTransactionDatatable;
use App\Http\Requests\client\AddClientRequest;
use App\Http\Requests\client\UpdateClientRequest;
use App\Models\Client;
use App\Models\ClientTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-clients')->only('index');
        $this->middleware('permission:create-clients')->only('create');
        $this->middleware('permission:update-clients')->only('edit');
        $this->middleware('permission:delete-clients')->only('destroy');
    }

    public function index(ClientDatatable $clientDatatable,Request $request)
    {
        $monthName = '';
        if ($request -> month){
            $monthName = Carbon::now()->monthName;
        }
        return $clientDatatable -> render('admin.clients.index', compact('monthName'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(AddClientRequest $request)
    {
        Client::create($request -> all());
        return redirect() -> route('admin.clients.index') -> with('success', __('trans.client added successfully'));
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit',compact('client'));
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request -> all());
        return redirect() -> route('admin.clients.index') -> with('success', __('trans.client edit successfully'));
    }


    public function destroy($id)
    {
        Client::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.client delete successfully'));
    }

    public function clientTransactions($client_id, ClientTransactionDatatable $clientTransactionDatatable,Request $request)
    {
//        dd($request -> client_id);
        // show client transactions Page
        $client = Client::findOrFail($client_id);
//        $transactions = ClientTransaction::where('client_id', $client_id)->orderByDesc('id')->paginate(5);
        return $clientTransactionDatatable -> render('admin.clients.transactions', compact('client'));
    }

    public function get_all_clients_by_ajax(Request $request)
    {
//        dd($request -> all());
        $clients = Client::pluck('name', 'id');
//        $clients = Client::where('name', 'like', '%'.$request -> search.'%')->get();
//        $response = [];
//        foreach ($clients as $client)
//        {
//            $response [] = [
//                'id' => $client -> id,
//                'text' => $client -> name,
//            ];
//        }
//        echo json_encode($response);
//        exit;
        return response() -> json(['clients' => $clients], 200);
    }
}
