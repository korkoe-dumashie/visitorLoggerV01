<?php

namespace App\Http\Controllers;

use App\Models\{Activities,Department, Employee};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{


        public function index(){
            return view('staff.index', [
                'employees' => Employee::whereNot('employment_status','inactive')->with('department')->get()
            ]);
        }
        public function create(){
            $departments = Department::get();
            return view('staff.create', compact('departments'));
        }


        public function store(Request $request){

            // dd(request());

            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'other_name' => '',
                // 'employee_number' => 'required',
                'email' => 'email|unique:employees,email|nullable',
                'phone_number' => 'required',
                'department_id' => 'required|exists:departments,id',
                'vehicle_number' => '',
                'job_title' => 'required',
                'access_card_number' => '',
                'gender'=> 'required',

            ]);

            Log::info('Request data:', $request->all());

            $phone = request()->phone_number;

            $formattedPhone = preg_replace('/^0/','233',$phone);



            Employee::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'other_name' => request('other_name'),
                'employee_number' => request('employee_number'),
                'email' => request('email'),
                'phone_number' => $formattedPhone,
                'department_id' => request('department_id'),
                // 'vehicle_number' => request('vehicle_number'),
                'job_title' => request('job_title'),
                'access_card_number' => request('access_card_number'),
                'gender' => request('gender')
            ]);

            Activities::log(
                action: 'Created New Staff'
            );



            return redirect('staff');

        }


        public function exportEmployees()
{
    $filePath = storage_path('app/employees_export_'.now()->format('Y-m-d_His').'.csv');

    $header = [
        'first_name',
        'last_name',
        'other_name',
        'employee_number',
        'email',
        'phone_number',
        'department',
        'job_title',
        'access_card_number',
        'gender',
        'employment_status',
    ];

    $file = fopen($filePath, 'w');
    fputcsv($file, $header);

    $employees = Employee::with('department')->get();
    foreach ($employees as $employee) {
        fputcsv($file, [
            $employee->first_name,
            $employee->last_name,
            $employee->other_name ?? '',
            $employee->employee_number ?? '',
            $employee->email ?? '',
            $employee->phone_number,
            $employee->department->name ?? '',
            $employee->job_title,
            $employee->access_card_number ?? '',
            $employee->gender,
            $employee->employment_status,
        ]);
    }

    fclose($file);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

public function exportTemplate()
{
    $filePath = storage_path('app/employees_import_template.csv');

    $header = [
        'first_name',
        'last_name',
        'other_name',
        'employee_number',
        'email',
        'phone_number',
        'department',
        'job_title',
        'access_card_number',
        'gender',
        'employment_status',
    ];

    $file = fopen($filePath, 'w');
    fputcsv($file, $header);

    fputcsv($file, [
        'John',
        'Doe',
        'Michael',
        'EMP001',
        'john.doe@example.com',
        '233241234567',
        'IT',
        'Software Developer',
        'AC001',
        'male',
        'active',
    ]);

    fclose($file);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

public function showImport()
{
    return view('staff.import');
}

public function importEmployees(Request $request)
{
    $request->validate([
        'importFile' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    try {
        $path = $request->file('importFile')->getRealPath();
        $file = fopen($path, 'r');

        $expectedHeader = [
            'first_name', 'last_name', 'other_name', 'employee_number',
            'email', 'phone_number', 'department', 'job_title',
            'access_card_number', 'gender', 'employment_status'
        ];

        $header = fgetcsv($file);

        if ($header !== $expectedHeader) {
            fclose($file);
            return back()->withErrors(['importFile' => 'Invalid file format. Please use the correct template.']);
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file)) !== false) {
            $rowNumber++;

            if (empty(array_filter($row))) {
                continue;
            }

            $firstName = trim($row[0]);
            $lastName = trim($row[1]);
            $otherName = !empty($row[2]) ? trim($row[2]) : null;
            $employeeNumber = !empty($row[3]) ? trim($row[3]) : null;
            $email = !empty($row[4]) ? trim($row[4]) : null;
            $phoneNumber = trim($row[5]);
            $departmentName = strtolower(trim($row[6]));
            $jobTitle = trim($row[7]);
            $accessCardNumber = !empty($row[8]) ? trim($row[8]) : null;
            $gender = strtolower(trim($row[9]));
            $employmentStatus = !empty($row[10]) ? strtolower(trim($row[10])) : 'active';

            if (empty($firstName) || empty($lastName) || empty($phoneNumber) || empty($departmentName) || empty($jobTitle)) {
                $errors[] = "Row {$rowNumber}: First name, last name, phone, department, and job title are required";
                $errorCount++;
                continue;
            }

            $department = Department::where('name', $departmentName)->first();
            if (!$department) {
                $errors[] = "Row {$rowNumber}: Department '{$departmentName}' does not exist";
                $errorCount++;
                continue;
            }

            $formattedPhone = preg_replace('/^0/', '233', $phoneNumber);

            try {
                Employee::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'other_name' => $otherName,
                    'employee_number' => $employeeNumber,
                    'email' => $email,
                    'phone_number' => $formattedPhone,
                    'department_id' => $department->id,
                    'job_title' => $jobTitle,
                    'access_card_number' => $accessCardNumber,
                    'gender' => $gender,
                    'employment_status' => $employmentStatus,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        fclose($file);

        Activities::log(action: "Imported {$successCount} employees from CSV");

        if ($successCount > 0) {
            $message = "Successfully imported {$successCount} employee(s)";
            if ($errorCount > 0) {
                $message .= " with {$errorCount} error(s)";
            }
            return redirect('staff')->with('success', $message)->with('import_errors', array_slice($errors, 0, 10));
        } else {
            return back()->withErrors(['importFile' => 'No employees were imported. ' . implode(', ', array_slice($errors, 0, 5))]);
        }

    } catch (\Exception $e) {
        Log::error('Employee import error', ['error' => $e->getMessage()]);
        return back()->withErrors(['importFile' => 'Import failed: ' . $e->getMessage()]);
    }
}

        public function show(Employee $staff){

        return view('staff.show', ['employees' => $staff]);
        }


        public function edit(Employee $employee){
            $departments = Department::get();

            // $employee = Employee::find($staff);
            // dd($employee);

        return view('staff.edit', [
            'employee' => $employee,
            'departments' => $departments
        ]);
        }


        public function update(Request $request, Employee $employee){
            $request->validate([
                'first_name'=>'required',
                'other_name'=>'',
                'last_name'=>'required',
                'email'=>'email|required|unique:employees,email,'.$employee->id,
                'phone_number'=>'required',
                'department_id'=>'required|exists:departments,id',
                'job_title'=>'required',
                'employment_status'=>'required|in:active,inactive,on_leave',
                'access_card_number'=>'',
                'gender'=>'required',
                'employee_number'=>''

            ]);

            $employee->update([
                'first_name'=>$request->first_name,
                'other_name'=>$request->other_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'phone_number'=>$request->phone_number,
                'department_id'=>$request->department_id,
                'job_title'=>$request->job_title,
                'employment_status'=>$request->employment_status,
                'access_card_number'=>$request->access_card_number,
                'gender'=>$request->gender,
                'employee_number'=>$request->employee_number
            ]);

            return redirect('staff')->with('success','Staff Updated Successfully');

        }





















}
