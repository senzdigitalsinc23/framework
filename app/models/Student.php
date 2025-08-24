<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Session;
use Database\ORM\Model;

class Student extends Model
{

    
    protected static string $table = 'students';

    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public ?string $created_at;
    public ?string $updated_at;
    public string $status;
    public ?string $is_super_admin;
    public ?int $role_id;

    /**
     * Hide password when converting to array.
     */
    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'student_id'    => $this->student_id,
            'first_name'  => $this->first_name,
            'surname_name'  => $this->surname_name,
            'other_name(s)'  => $this->other_name,
            'dob'   => $this->dob,
            'gender'  => $this->gender,
            'phone'  => $this->phone,
            'nationality'  => $this->nationality,
            'city'  => $this->city,
            'hometown'  => $this->hometown,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by'  => $this->created_by
        ];
    }

    public function __construct() {
        //$this->db = Database::getInstance()->getConnection();
    }

}
