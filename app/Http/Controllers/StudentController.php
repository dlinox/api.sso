<?php

namespace App\Http\Controllers;

use App\Models\Student;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{

    protected $student;

    public function __construct()
    {
        $this->student = new Student();
    }

    public function items(Request $request)
    {

        $query = $this->student->query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtros dinámicos
        if ($request->has('filters') && is_array($request->filters)) {
            foreach ($request->filters as $filter => $value) {
                // Aquí puedes agregar validaciones adicionales según sea necesario
                if (!is_null($value)) {
                    $query->where($filter, $value);
                }
            }
        }

        if ($request->has('sortBy') && is_array($request->sortBy)) {
            foreach ($request->sortBy as $sort) {
                $query->orderBy($sort['key'], $sort['order']);
            }
        }

        $perPage = $request->itemsPerPage == -1
            ? $query->count()
            : ($request->itemsPerPage ?? 10);

        $items = $query->latest()->paginate($perPage);
        return response()->json($items);
    }

    public function index()
    {
        $professors = Student::latest()->get();
        return response()->json($professors);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:100',
                'paternal_surname' => 'nullable|max:80|required_without:maternal_surname',
                'maternal_surname' => 'nullable|max:80|required_without:paternal_surname',
                'document_type' => 'required|max:3',
                'document_number' => 'required|max:20',
                'birthdate' => 'nullable|date',
                'phone_number' => 'nullable|digits:9',
                'career_code' => 'nullable|max:3',
                'student_code' => 'nullable|max:20',
                'email' => 'nullable|email|max:255',
                'gender' => 'nullable|max:1',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'paternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'paternal_surname.max' => 'El apellido paterno no debe exceder los 80 caracteres',
                'maternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'maternal_surname.max' => 'El apellido materno no debe exceder los 80 caracteres',
                'document_type.required' => 'El tipo de documento es obligatorio',
                'document_number.required' => 'El número de documento es obligatorio',
                'status.required' => 'El estado es obligatorio'
            ]
        );

        try {
            $professor = Student::create($request->except('id'));
            return response()->json($professor);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|max:100',
                'paternal_surname' => 'nullable|max:80|required_without:maternal_surname',
                'maternal_surname' => 'nullable|max:80|required_without:paternal_surname',
                'document_type' => 'required|max:3',
                'document_number' => 'required|max:20|unique:students,document_number,' . $id,
                'birthdate' => 'nullable|date',
                'phone_number' => 'nullable|digits:9',
                'career_code' => 'nullable|max:3',
                'student_code' => 'nullable|max:20',
                'email' => 'nullable|email|max:255',
                'gender' => 'nullable|max:1',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'paternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'paternal_surname.max' => 'El apellido paterno no debe exceder los 80 caracteres',
                'maternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'maternal_surname.max' => 'El apellido materno no debe exceder los 80 caracteres',
                'document_type.required' => 'El tipo de documento es obligatorio',
                'document_number.required' => 'El número de documento es obligatorio',
                'status.required' => 'El estado es obligatorio'
            ]
        );

        try {
            $professor = Student::find($id);
            $professor->update($request->except('id'));
            return response()->json($professor);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function receiveStudent(Request $request,  $document)
    {
        $student = Student::where('student_code', $document)->exists();
        if (!$student) {
            $student  = Student::create([
                'name' => $request->name,
                'paternal_surname' => $request->lastName,
                'maternal_surname' => $request->lastName2,
                'document_type' => $request->documentType == 'DNI' ? '001' : '000',
                'document_number' => $request->document,
                'career_code' => $request->professionalSchoolCode,
                'student_code' => $request->studentCode,
                'location' => $request->ubigeo,
                'email' => $request->email,
                'phone_number' => $request->phoneNumber,
                'mother_name' => $request->motherFullName,
                'father_name' => $request->fatherFullName,
                'status' => true
            ]);
            return response()->json(true);
        }
        return response()->json($student);
    }
    //getByDocument
    public function getByDocument($document)
    {
        $student = Student::where('student_code', $document)->first();
        return response()->json($student);
    }


    public function getStudentByCode($code)
    {

        try {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://38.43.133.27/CONSULTAS_BIENESTAR/v1/student/byCode/' . $code . '/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiI1NmRiMThjMS00ODU1LTg4ODgtYzI5ZS0wMDAwM2ZhMTkxNTYiLCJzdWIiOiJGRUE5MDVDRS0zRDYxLTExRUYtOEM1Qy0wMDUwNTY4OTAwM0QiLCJleHAiOjE3NTIwMDQ0MTYsIm5iZiI6MTcyMDQ2ODQxNywiaWF0IjoxNzIwNDY4NDE2LCJqdGkiOiJNVGN5TURRMk9EUXhOZz09In0.BGiW_HKnnyImObXWytQLi_c93TgutdoJdhW7fYwNuKsEvIDbahe0DbsJaIwHtdTj1EYaqPp_fcz8IbxfHIftsw'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return response()->json(json_decode($response));
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                500
            );
        }
    }
}
