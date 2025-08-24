<?php
namespace App\Controllers\Api\v1;

use App\Core\Request;
use App\Core\Storage;
use App\Core\Validator;
use App\Core\View;
use App\Helpers\Auth;
use App\Models\Student;
use Services\Reports\PdfReport;
use Services\Reports\ReportFactory;

class StudentController
{
    public function __construct() {
        if(! isLoggedIn()){
            return response()->json([
                'success' => false, 
                'message' => "You need to log in",
                'redirect' => '/web/login'
            ]);
        }
    }

    public function index()
    {
        header('Content-Type: application/json');

        $students = Student::all();

        if ($students) {
            return response()->json([
                'success' => true, 
                'message' => "All students successfully fetched.", 
                'students'   => $students
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => "No Data Found"
        ]);
    }

    public function create(Request $request) {
         $status = '';
        $message = '';
        $code  = '';

        $data = $request->getPost();

        $validator = new Validator($data, [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if (User::where('email', $data['email'])) {
            return response()->json(['success' => false, 'message' => 'Email already taken'], 409);
        }else if ($validator->fails()) {          

            $errors = '';

            foreach ($validator->errors() as $values) {
                foreach ($values as $value) {
                    $errors .= $value . "\n";
                }
            }

            $status = false;
            $message = "<pre>" . $errors . "</pre>";
            $code = 000;

        }else {
            $status = true;
            $message = 'User successfully registered';
            $code = 201;

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            if(isset($data['role'])) { $data['role_id'] = $data['role'];unset($data['role']);}
            if(isset($data['_token'])) {unset($data['_token']);}

            User::create($data);
        } 
        
        return response()->json(['success' => $status, 'message' => $message, 'user' => $data], $code);
        //return $this->jsonResponse(201, ['message' => 'User registered successfully', 'user' => $user]);
    }

    // Export students to CSV
    public function exportCsv()
    {
        $filename = "students_export_" . date("Y-m-d_H-i-s") . ".csv";

        // Set headers for browser download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Open output buffer as a "file"
        $output = fopen('php://output', 'w');

        $students = Student::all();

        $columns = array_keys($students[2]);

       /*  [
            'Admission No', 'First Name', 'Last Name', 'Email', 'Phone',
            'Gender', 'DOB', 'Class', 'Address', 'Guardian Name',
            'Guardian Contact', 'Status'
        ] */

        // CSV Header row
        fputcsv($output, $columns);

        // Fetch all students
        foreach ($students as $student) {
            foreach ($columns as $column) {
                $student[$column] = $student[$column];
            }

            fputcsv($output, $student);
        }

        fclose($output);
        exit; // Important to stop any extra output
    }

    // Import students from CSV
    public function importCsv()
    {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
            $file = fopen($_FILES['csv_file']['tmp_name'], 'r');

            // Skip header row
            $headers = fgetcsv($file); 

            $count = 0;

            $students = [];


           // echo json_encode(['success' => true, 'message' => 'Data imported successfully', 'data' => $headers]);exit;

            while (($row = fgetcsv($file)) !== false) {
                while ($count <= (count($row) - 1)) {
                    $students[$headers[$count]] = $row[$count];

                    $count++;
                    //echo json_encode(['success' => true, 'message' => 'Data imported successfully', 'data' => $students]);exit             
                } 

                Student::create($students);
                $count = 0;
                
            }

            fclose($file);

            echo json_encode(['success' => true, 'message' => '✅ Students imported successfully!', 'total' => $students]);exit;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Please upload a valid CSV file.']);exit;
        }
    }

    // Step 1: Preview CSV
    public function previewCSV(Request $request)
    {
        if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid file upload']);
            return;
        }

        $file = $_FILES['csvFile']['tmp_name'];
        $rows = [];
        if (($handle = fopen($file, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ","); // Get first row as header
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $rows[] = array_combine($header, $data);
            }
            fclose($handle);
        }

        echo json_encode([
            'success' => true,
            'header' => $header,
            'rows' => $rows
        ]);
    }

}
