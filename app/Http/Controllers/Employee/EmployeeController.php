<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\EmployeeDatatable;
use App\Http\Controllers\Controller;
use App\Interfaces\user\generate;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\Nationality;
use App\Models\Salary;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-employees')->only('index');
        $this->middleware('permission:create-employees')->only('create');
        $this->middleware('permission:update-employees')->only('edit');
        $this->middleware('permission:delete-employees')->only('destroy');
    }

    public function index(EmployeeDatatable $employeeDatatable)
    {
        $branchName = '';
        if (request('branch_id')) {
            $branchName = Branch::findOrFail(request('branch_id')) -> name; // Get [ branch name ] to send into view
        }
        return $employeeDatatable -> render('employee.employees.index', compact('branchName'));
    }

    public function create()
    {
        $jobTitles = JobTitle::pluck('name', 'id') -> toArray();
        $branches = Branch::pluck('name', 'id') -> toArray();
        $nationalities = Nationality::pluck('nationality', 'id') -> toArray();
        return view('employee.employees.create', compact('jobTitles', 'branches', 'nationalities'));
    }

    public function store(Request $request)
    {
//        dd($request -> all());
        $generate = new generate();
        $username = $generate-> username();
        $text_password = $generate-> password()['text_password'];
        $hashed_password = $generate-> password()['hashed_password'];
        $user_credentials = ['username' => $username, 'hashed_password' => $hashed_password, 'text_password' => $text_password];
        $request_except = [
            'active_date_leaving',
            'active_other_allowance',
            'username',
            'password',
            'main',
            'housing_allowance',
            'transfer_allowance',
            'travel_allowance',
            'end_service_allowance',
            'other_allowance',
            'description_of_other_allowance',
        ];
        $employee = Employee::create($request -> except($request_except) + $user_credentials);

        $salary = [
            'main' => $request-> main,
            'housing_allowance' => $request-> housing_allowance,
            'transfer_allowance' => $request-> transfer_allowance,
            'travel_allowance' => $request-> travel_allowance,
            'end_service_allowance' => $request-> end_service_allowance,
            'other_allowance' => $request-> other_allowance,
            'description_of_other_allowance' => $request-> description_of_other_allowance,
        ];
        $employee -> salary()->create($salary);
        return redirect() -> route('employee.employees.index') -> with('success', __('trans.employee added successfully'));
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employee.employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $jobTitles = JobTitle::pluck('name', 'id') -> toArray();
        $nationalities = Nationality::pluck('nationality', 'id') -> toArray();
        $employee = Employee::findOrFail($id);
        $branches = Branch::pluck('name', 'id') -> toArray();
        $salary = Salary::findOrFail($employee->id);
//        dd($salary);
        return view('employee.employees.edit',compact('employee', 'branches', 'jobTitles', 'nationalities', 'salary'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee_data = $request -> only (
            'name',
            'birth_date',
            'date_of_hiring',
            'job_title_id',
            'id_number',
            'passport_number',
            'branch_id',
            'nationality_id',
            'date_of_leaving_work',
        );
        if ($request -> password != NULL)
            $employee_data['hashed_password'] = bcrypt($request -> password);
        $date_of_leaving_work_value = $request -> date_of_leaving_work;



        $date_of_leaving_work = ['date_of_leaving_work' => $date_of_leaving_work_value];

        $employee ->update($employee_data+ $date_of_leaving_work+ ['status' => $date_of_leaving_work_value ? 'inactive': 'active']);

        $salary_data = $request -> only (
            'main',
            'housing_allowance',
            'transfer_allowance',
            'travel_allowance',
            'end_service_allowance',
            'other_allowance',
            'description_of_other_allowance',
            );
        $collection_salary_data = [
            'end_service_allowance' => $request ->end_service_allowance,
            'other_allowance' => $request ->other_allowance,
            'description_of_other_allowance' => $request ->description_of_other_allowance,
        ];

        $employee -> salary()->update($salary_data+$collection_salary_data);

        return redirect() -> back();
//        return redirect() -> route('employee.employees.index') -> with('success', __('trans.employee edit successfully'));
    }


    public function destroy($id)
    {
        Employee::findOrFail($id) -> delete();
        return redirect()->back()->with('delete', __('trans.employee delete successfully'));
    }
}
