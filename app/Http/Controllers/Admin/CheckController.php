<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CheckDatatable;
use App\Exports\CheckExport;
use App\Exports\CheckExportView;
use App\Http\Requests\Check\AddAndUpdateCheckRequest;
use App\Http\Requests\DeviceReportRequest;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Check;
use App\Models\CheckStatus;
use App\Models\Client;
use App\Models\Engineer;
use App\Models\File;
use App\Models\Images;
use App\Models\Relation_check_technical;
use App\Models\Technical;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class CheckController extends Controller
{
    use HelperTrait;
    public function __construct()
    {
        $this->middleware('permission:read-check')->only('index');
        $this->middleware('permission:create-check')->only('create');
        $this->middleware('permission:update-check')->only('edit');
        $this->middleware('permission:delete-check')->only('destroy');
        $this->middleware('permission:read-managementNotes')->only('managementNotes');
        $this->middleware('permission:update-checkStatus')->only('onlyEditCheckStatus');
    }

    public function generate_pdf($check_id)
    {

        $data['check'] = Check::findOrFail($check_id);
        $data['client_signature_entry'] = Images::where('check_id',$check_id)->where('type',3) -> latest() -> first();
        $data['client_signature_exit'] = Images::where('check_id',$check_id)->where('type',4) -> latest() -> first();
        $mpdf = PDF::loadView('admin.check.generate_pdf', $data, [], [
            'margin_top' => 80,
            'margin_header' => 10,
            'margin_footer' => 10,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;
//        $mpdf->defaultFont = 'ReemKufi';
        return $mpdf->download($data['check']->check_number.'.pdf');
//        return $mpdf->stream($data['check']->check_number.'.pdf');
    }

    public function index(Request $request, CheckDatatable $checkDatatable)
    {
        $checkStatusName = '';
        $branchName = '';
        $clientName = '';
        $carExists = '';
        $car = '';
        if ($request->check_status_id) // Get all check with [ check status id ] from request url
            $checkStatusName = CheckStatus::findOrFail($request->check_status_id)->name; // Get [ check status name ] to send into view

        if ($request->branch_id) { // Get all check with [ branch id ] from request url
            $branchName = Branch::findOrFail($request->branch_id)->name; // Get [ branch name ] to send into view
            if ($request -> car_exists == true)
            {
                $carExists = 'السيارات الموجودة حالياً بالفرع';
            }
        }
        if ($request->client_id) // Get all check with [ client id ] from request url
            $clientName = Client::findOrFail($request->client_id)->name; // Get [ client name ] to send into view

        if ($request->car_id) // Get all check with [ car id ] from request url
            $car = Car::findOrFail($request -> car_id); // Get [ Car ] to send into view

        return $checkDatatable->render('admin.check.index', compact('checkStatusName', 'branchName', 'clientName', 'carExists', 'car'));
    }

    public function create(Request $request)
    {
        $branch_id = $request ->get('branch_id') ?? Auth::user()->branch_id;
        $car_id = $request ->get('car_id') ?? '';
        $targetCar = Car::findOrFail($car_id);
        $client_id = $request ->get('client_id');
        $targetClient = Client::findOrFail($client_id);
        $technicals = Technical::where('branch_id', $branch_id) -> select('id', 'name') -> get();
        $engineers = Engineer::where('branch_id', $branch_id) -> pluck('name', 'id') -> toArray();

        return view('admin.check.create', compact('technicals', 'targetClient', 'targetCar', 'branch_id', 'client_id', 'car_id', 'engineers'));
    }

    public function store(AddAndUpdateCheckRequest $request)
    {
//        dd($request -> all());
        $branch_id = $request -> branch_id && $request -> branch_id != null ? $request -> branch_id : Auth::user()->branch_id;
        $check_number = rand();
        $check_data = $request -> except('car_images', 'technical_id'); // Get All Column Without [car_images, technical_id]
        $allData = $check_data+ ['user_id' => Auth::user()->id] + ['check_number' => $check_number] + ['branch_id' => $branch_id];
        $check = Check::create($allData); // Create New check From [check_data] Request And [image_data] Coming With Trait
        $check_id = $check->id;
        if ($request -> has('car_images')) {
            $image_data = $this->uploadImageProcessing($request -> car_images, 'check_cars', 'shocks', $check_number, 'public', 500, 500); // Upload Image With Trait
            foreach ($image_data  as $image)
            {
                // Type => 1 [ 1 For car_images ]
                $check -> images() -> create($image + ['type'=>1]);
            }
        }

        if ($request -> has('technical_id'))
        {
            $technicalsIds = $request -> technical_id;
            $check -> technicals() -> attach($technicalsIds);
        }
        return redirect() -> route('admin.check.clientSignature', [$check_id, $check_number]) -> with('success', __('trans.check added successfully'));
    }

    public function show($id)
    {
        $check = Check::findOrFail($id);
        return view('admin.check.show', compact('check'));
    }

    public function edit($id)
    {
        $check = Check::findOrFail($id);
        if (Auth::user()->hasRole('branch_manager') && $check->branch_id != Auth::user()->branch_id)
            abort(403); // no edit if User not who set check data
        $checkStatus = CheckStatus::pluck('name', 'id')->toArray();
        $technicals = Technical::where('branch_id', $check -> branch_id) -> select('id', 'name') -> get();
        $engineers = Engineer::where('branch_id', Auth::user()->branch_id) -> pluck('name', 'id') -> toArray();
        $check_technicals = Relation_check_technical::where('check_id', $id) -> pluck('id', 'technical_id') -> toArray();
        // Send to view if need disabled check status item from select box option
/*        $option_attributes = collect(CheckStatus::all())
            ->mapWithKeys(function ($item) use ($check) {
                if ($check -> client_approved == 0) {
                    if ($item->name == 'تم التعميد من قبل العميل') {
                        return [$item->id => ['disabled' => true, 'value' => '']];
                    }
                }else {
                    if ($item->name == 'تم التعميد من قبل العميل') {
                        return [$item->id => ['disabled' => false]];
                    }
                }
                return [];
            })->all();*/

        return view('admin.check.edit',compact('check', 'checkStatus', 'technicals', 'engineers', 'check_technicals'));
    }

    public function update(AddAndUpdateCheckRequest $request, $id)
    {
        $check = Check::findOrFail($id);
        $check -> update($request -> except(['technical_id']));

        if ($request -> has('technical_id'))
        {
            $technicalsIds = $request -> technical_id;
            $check -> technicals() -> sync($technicalsIds);
        }
        return redirect() -> route('admin.check.index') -> with('success', __('trans.check edit successfully'));
    }

    public function destroy($id)
    {
        $check = Check::findOrFail($id);
        $this -> deleteImageHandel('public', $check -> images); // Check If check Have Directory Profile Picture Delete It
        $check -> delete(); // Delete check From check Table
        return redirect()->back()->with('delete', __('trans.check delete successfully'));
    }

    // Custom Function images
    public function images($check_id,$type)
    {
        $images = Images::where('check_id',$check_id)->where('type',$type) -> get();
        $file = File::where('check_id',$check_id)->where('type',$type) -> first();
        return view('admin.check.images', compact('images', 'file'));
    }

    // Custom Function create_device_report
    public function create_device_report($check_id, $check_number)
    {
        return view('admin.check.createDeviceReport', compact('check_id', 'check_number'));
    }

    // Custom Function store_device_report
    public function store_device_report(DeviceReportRequest $request)
//    public function store_device_report(DeviceReportRequest $request)
    {
        $check_id = $request -> check_id;
        $check_number = $request -> check_number;
        /* check if all inputs empty redirect previous page with error */
        if (empty($request->report_device) && empty($request->report_device_file))
        {
            return redirect()->back()->with('error', 'من فضلك قم برفع تقرير جهاز الفحص');
        }
        /* if upload images */
        if ($request -> has('report_device')) {
            // Type => 2 [ 2 For Device Report ]
            $allCheckData = ['type' => 2, 'check_id' => $check_id];
            $image_data = $this->uploadImageProcessing($request -> report_device, 'check_cars', 'reports', $check_number, 'public', 500, 500); // Upload Image With Trait
            foreach ($image_data  as $image)
            {
                Images::create($image + $allCheckData);
            }
        }else{
            /* if upload file */
            $file = $request->report_device_file;
            $fileName = md5(date("dmYhisA"));
            $fileExtension = $file->getClientOriginalExtension();
            $fullName = $fileName . '.' . $fileExtension;
            $check_number = $request -> check_number;
            $path = 'check_cars/device_reports_file';
            // Type => 2 [ 2 For Device Report ]
            $allCheckData = ['type' => 2, 'check_id' => $check_id];

            /* check if directory empty */
            if (Storage::disk('public')->exists('check_cars/device_reports_file/'.$check_number))
            {
                $files_count = Storage::disk('public')->files('check_cars/device_reports_file/'.$check_number);
                if (count($files_count) > 0)
                {
                    return redirect() ->back() -> with('error', 'تم رفع التقرير من قبل');
                }
            }
            /* upload file */
            $file_data = $this->uploadFileProcessing($file, 'public', $check_number, $fileName, $path, $fileExtension);

            /* save file to database */
            File::create($allCheckData + $file_data);
        }
        return redirect() -> route('admin.check.index') -> with('success', __('trans.device report added successfully'));
    }

    // Custom Function client_eture
    public function client_signature($check_id ,$check_number)
    {
        return view('admin.check.signature', compact('check_id', 'check_number'));
    }

    // Custom Function signature
    public function signature(Request $request)
    {
        if ($request -> ajax()) {
            if ($request -> car_status) {
                // If the car gets exit
                $check_status = CheckStatus::where('name', 'تم تسليم السيارة الى العميل')->select('id')->first();
                $check = Check::findOrFail($request -> check_id);
                $check -> update(['check_status_id' => $check_status -> id, 'car_exit_date' => now()]);
                $image_data = $this -> uploadSVGImage($request -> check_id,$request -> image_data,'check_cars', 'signature_exit',$request -> check_number,'public');
                // Type => 4 [ 4 For client signature exit]
                Images::create($image_data+ ['type' => 4]+ ['check_id' => $request -> check_id]);
            }else {
                // In the event car of entry
                $image_data = $this -> uploadSVGImage($request -> check_id,$request -> image_data,'check_cars', 'signature_entry',$request -> check_number,'public');
                // Type => 3 [ 3 For client signature entry]
                Images::create($image_data+ ['type' => 3]+ ['check_id' => $request -> check_id]);
            }
        }
        return redirect() -> route('admin.check.invoice_pdf') -> with('success', __('trans.client signature added successfully'));
//        return redirect() -> route('admin.check.index') -> with('success', __('trans.client signature added successfully'));
    }

    // Custom Function onlyEditCheckStatus
    public function onlyEditCheckStatus(Request $request)
    {
        if ($request -> ajax()) {
            $check = Check::findOrFail($request -> check_id);
            $check -> update(['check_status_id' => $request -> check_status_id]);
            return response(session()->flash('status_success', __('trans.check status edit successfully')));
        }
    }

    // Custom Function receipt
    public function receipt($check_id)
    {
        $check = Check::findOrFail($check_id);
        $client_signature_entry = Images::where('check_id',$check_id)->where('type',3) -> latest() -> first();
        $client_signature_exit = Images::where('check_id',$check_id)->where('type',4) -> latest() -> first();
        return view('admin.check.receipt', compact('check', 'client_signature_entry', 'client_signature_exit'));
    }

    // Custom function managementNotes
    public function managementNotes($check_id, Request $request)
    {
        $check = Check::findOrFail($check_id);
        return view('admin.check.managementNotes', compact('check'));
    }

    // Custom function managementNotes
    public function managementNotesUpdate($check_id, Request $request)
    {
        $check = Check::findOrFail($check_id);
        $check -> update(['management_notes' => $request -> management_notes]);
        return redirect() -> route('admin.check.index') -> with('success', __('trans.management notes edit successfully'));
    }

    public function selectBranch()
    {
        $branches = Branch::pluck('name', 'id');
        return view('admin.check.selectBranch', compact('branches'));
    }

    public function export_pdf(Request $request)
    {
        $checks = $request -> checks;
        return  view('admin.check.export_pdf', compact('checks'));
    }

    public function receipt_print(Request $request)
    {
//        dd($request->all());
        $checkId = $request -> check_id;
        $client_signature_entry = Images::where('check_id',$checkId)->where('type',3) -> latest() -> first();
        $client_signature_exit = Images::where('check_id',$checkId)->where('type',4) -> latest() -> first();

        $data['check'] = Check::findOrFail($checkId);
        $data['client_signature_entry'] = $client_signature_entry;
        $data['client_signature_exit'] = $client_signature_exit;
        $mpdf = PDF::loadView('admin.check.receipt_print', $data, [], [
            'margin_top' => 110,
            'margin_header' => 10,
            'margin_footer' => 10,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;

        return $mpdf->download($data['check']->check_number.'.pdf');
    }

    public function receipt_download_without_last_page(Request $request)
    {
//        dd($request->all());
        $checkId = $request -> check_id;
        $client_signature_entry = Images::where('check_id',$checkId)->where('type',3) -> latest() -> first();
        $client_signature_exit = Images::where('check_id',$checkId)->where('type',4) -> latest() -> first();

        $data['check'] = Check::findOrFail($checkId);
        $data['client_signature_entry'] = $client_signature_entry;
        $data['client_signature_exit'] = $client_signature_exit;
        $mpdf = PDF::loadView('admin.check.receipt_download_without_last_page', $data, [], [
            'margin_top' => 110,
            'margin_header' => 10,
            'margin_footer' => 10,

        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->showImageErrors = true;
        $mpdf->setAutoBottomMargin = true;

        return $mpdf->download($data['check']->check_number.'.pdf');
    }

}
