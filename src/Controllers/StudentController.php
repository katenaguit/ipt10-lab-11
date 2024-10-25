<?php

namespace App\Controllers;

use App\Models\Student;
use App\Controllers\BaseController;

class StudentController extends BaseController
{
    public function list()
    {
        $studentModel = new Student();
        $students = $studentModel->all();

        $template = 'students';
        $data = [
            'students' => $students 
        ];

        return $this->render($template, $data);
    }

    public function viewStudent($student_code)
    {
        $studentModel = new Student();
        $student = $studentModel->find($student_code); 

        if (!$student) {
            return $this->render('error', ['message' => 'Student not found.']);
        }

        $template = 'single-student';
        $data = [
            'student' => $student
        ];

        return $this->render($template, $data);
    }
}
