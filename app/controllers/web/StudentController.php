<?php

namespace App\Controllers\Web;

use App\Core\Request;
use App\Core\Response;
use App\Models\Student;
use App\Requests\StudentRequest;

class StudentController
{
    public function index()
    {
        exit;
        $students = Student::all();
        return Response::view('students/index', ['students' => $students]);
    }

    public function create()
    {
        return Response::view('students/create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $errors = StudentRequest::validate($data);
        if ($errors) {
            return Response::view('students/create', ['errors' => $errors, 'old' => $data]);
        }

        Student::create($data);
        return header('Location: /students');
    }

    public function edit(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            http_response_code(404);
            echo "Student not found";
            exit;
        }
        return Response::view('students/edit', ['student' => $student]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            http_response_code(404);
            echo "Student not found";
            exit;
        }

        $data = $request->all();
        $errors = StudentRequest::validate($data, $id);
        if ($errors) {
            return Response::view('students/edit', ['errors' => $errors, 'student' => $student]);
        }

        $student->update($data);
        return header('Location: /students');
    }

    public function delete(Request $request, $id)
    {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
        }
        return header('Location: /students');
    }
}
