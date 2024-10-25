<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Student extends BaseModel
{
    public $student_code;
    public $first_name;
    public $last_name;
    public $email;
    public $date_of_birth; 
    public $sex;         

    public function all()
    {
        $sql = "SELECT id, student_code, CONCAT(first_name, ' ',  last_name) AS student_name, first_name, last_name, email, date_of_birth, sex FROM students";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, '\App\Models\Student');
        return $result;
    }

    public function find($student_code)
    {
        $sql = "SELECT * FROM students WHERE student_code = :student_code";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':student_code', $student_code);
        $statement->execute();
        return $statement->fetchObject('\App\Models\Student');
    }

    public function getStudentCode() { return $this->student_code; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getEmail() { return $this->email; }
    public function getDateOfBirth() { return $this->date_of_birth; }
    public function getSex() { return $this->sex; }

    public function getFullName()
    {
        $sql = "SELECT first_name || last_name AS student_name FROM students";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, '\App\Models\Student');
        return $result;
    }
}
