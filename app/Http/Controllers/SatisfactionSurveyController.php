<?php

namespace App\Http\Controllers;

use App\Mail\SendSurveyMail;
use App\Models\SatisfactionSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SatisfactionSurveyController extends Controller
{


    public function store(Request $request)
    {

        $request->validate([
            'attention_id' => 'required|integer',
            'person_type' => 'required|string',
            'person_id' => 'required|integer',
        ]);

        try {


            $satisfactionSurvey = SatisfactionSurvey::create([
                'attention_id' => $request->attention_id,
                'person_type' => $request->person_type,
                'person_id' => $request->person_id,
                'user_id' => Auth::user()->id,
            ]);

            if ($satisfactionSurvey) {
                $token = Crypt::encryptString($satisfactionSurvey->id);
                Mail::to($request->email)->send(new SendSurveyMail($token));
                return response()->json(['message' => 'Correo enviado con éxito'], 201);
            }

            return response()->json(['message' => 'Error al enviar el correo'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getSurvey($token)
    {
        $id = Crypt::decryptString($token);
        $satisfactionSurvey = SatisfactionSurvey::select('score', 'comments')
            ->where('id', $id)
            ->first();
        return response()->json($satisfactionSurvey);
    }

    public function responseSurvey(Request $request, $token)
    {

        try {
            $id = Crypt::decryptString($token);
            $satisfactionSurvey = SatisfactionSurvey::find($id);
            $satisfactionSurvey->update([
                'score' => $request->score,
                'comments' => $request->comments,
            ]);
            return response()->json(['message' => 'Gracias por su respuesta']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
