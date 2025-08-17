<?php

namespace App\Controllers\Api;

use App\Models\Student;
use App\Core\Request;

class StudentController
{
    public function index()
    {
        echo json_encode(Student::all());
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return json_encode(['error' => 'Not found'], 404);
        }
        echo json_encode($student);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $student = Student::create($data);
        echo json_encode($student, 201);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return json_encode(['error' => 'Not found'], 404);
        }
        $student->update($request->all());
        echo json_encode($student);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return json_encode(['error' => 'Not found'], 404);
        }
        $student->delete();
        echo json_encode(['message' => 'Deleted']);
    }
}
