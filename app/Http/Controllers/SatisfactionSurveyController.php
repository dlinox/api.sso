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

        $satisfactionSurvey = SatisfactionSurvey::create([
            'attention_id' => $request->attention_id,
            'person_type' => $request->person_type,
            'person_id' => $request->person_id,
            'user_id' => Auth::user()->id,
        ]);

        if ($satisfactionSurvey) {
            $token = Crypt::encryptString($satisfactionSurvey->id);
            Mail::to($request->email)->send(new SendSurveyMail($token));
        }

        return response()->json($satisfactionSurvey);
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
        $id = Crypt::decryptString($token);
        $satisfactionSurvey = SatisfactionSurvey::find($id);
        $satisfactionSurvey->update([
            'score' => $request->score,
            'comments' => $request->comments,
        ]);
        return response()->json($satisfactionSurvey);
    }
}
