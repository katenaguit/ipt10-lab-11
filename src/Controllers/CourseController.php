<?php

namespace App\Controllers;

use App\Models\Course;
use App\Controllers\BaseController;
use Fpdf\Fpdf;

class CourseController extends BaseController
{
    public function list()
    {
        $courseModel = new Course();
        $courses = $courseModel->all();

        $template = 'courses';
        $data = [
            'items' => $courses
        ];

        return $this->render($template, $data);
    }

    public function viewCourse($course_code)
    {
        $courseModel = new Course();
        $course = $courseModel->find($course_code);
        $enrollees = $courseModel->getEnrolees($course_code);

        $template = 'single-course';
        $data = [
            'course' => $course,
            'enrollees' => $enrollees
        ];

        return $this->render($template, $data);
    }

    public function exportPDF($course_code) {
        $courseModel = new Course();
        
        $course = $courseModel->find($course_code);
        $enrollees = $courseModel->getEnrolees($course_code);
    
        $pdf = new FPDF();
        $pdf->AddPage();
    
        $pdf->SetFont('Courier', 'B', 22); 
        $pdf->SetTextColor(70, 130, 180); 
        $pdf->Cell(0, 10, 'Course Information', 0, 1, 'C');
    
        $pdf->SetDrawColor(173, 216, 230); 
        $pdf->Line(10, 25, 200, 25);
    
        $pdf->Ln(10);
        
        $pdf->SetFont('Courier', 'I', 14); 
        $pdf->SetTextColor(60, 60, 60); 
    
        $this->addCourseDetailRow($pdf, 'Course Code:', $course->course_code);
        $this->addCourseDetailRow($pdf, 'Course Name:', $course->course_name);
        $this->addCourseDetailRow($pdf, 'Description:', $course->description, true);
        $this->addCourseDetailRow($pdf, 'Credits:', $course->credits);
    
        $pdf->Ln(10);
        
        $pdf->SetFont('Courier', 'B', 20); 
        $pdf->SetTextColor(70, 130, 180); 
        $pdf->Cell(0, 10, 'List of Student Enrollees', 0, 1, 'C');  
    
        $pdf->Ln(5);
    
        $columnWidths = [
            'ID' => 20,
            'First Name' => 40,
            'Last Name' => 40,
            'Email' => 60,
            'Date of Birth' => 30,
            'Sex' => 20,
        ];
    
        $pdf->SetFont('Courier', 'B', 10); 
        $pdf->SetFillColor(70, 130, 180); 
        $pdf->SetTextColor(255, 255, 255); 
    
        $this->createTableHeader($pdf, $columnWidths);
        
        $pdf->SetFont('Courier', '', 10); 
        $pdf->SetTextColor(0, 0, 0); 
    
        if (!empty($enrollees)) {
            foreach ($enrollees as $enrollee) {
                $this->addEnrolleeRow($pdf, $columnWidths, $enrollee);
            }
        } else {
            $this->addNoEnrolleesMessage($pdf, $columnWidths);
        }
    
        $pdf->Output('D', 'course_' . $course_code . '_enrolledstudents.pdf');
    }
    
    private function addCourseDetailRow($pdf, $label, $value, $isMultiCell = false) {
        $pdf->SetFont('Courier', '', 10); 
        $pdf->Cell(50, 10, $label, 0, 0, 'L');
        if ($isMultiCell) {
            $pdf->MultiCell(0, 10, $value);
        } else {
            $pdf->Cell(0, 10, $value, 0, 1, 'L');
        }
    }
    
    private function createTableHeader($pdf, $columnWidths) {
        $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
        foreach ($columnWidths as $colName => $width) {
            $pdf->Cell($width, 10, $colName, 1, 0, 'C', true);
        }
        $pdf->Ln();
    }
    
    private function addEnrolleeRow($pdf, $columnWidths, $enrollee) {
        $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
        $pdf->Cell($columnWidths['ID'], 10, $enrollee["student_code"], 1);
        $pdf->Cell($columnWidths['First Name'], 10, $enrollee["first_name"], 1);
        $pdf->Cell($columnWidths['Last Name'], 10, $enrollee["last_name"], 1);
        $pdf->Cell($columnWidths['Email'], 10, $enrollee["email"], 1);
        $pdf->Cell($columnWidths['Date of Birth'], 10, $enrollee["date_of_birth"], 1);
        $pdf->Cell($columnWidths['Sex'], 10, $enrollee["sex"], 1);
        $pdf->Ln();
    }
    
    private function addNoEnrolleesMessage($pdf, $columnWidths) {
        $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
        $pdf->Cell(array_sum($columnWidths), 10, 'No enrollees found for this course.', 1, 1, 'C');
    }    
}
