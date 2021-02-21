<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Validator;

class SendMailController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function sendMail(Request $request) {
        $array = array(
            'rules' => [
                'to'=> 'required|email:dns',
                'subject'=> 'required',
                'message'=> 'required|min:10',
            ],
            'messages' => [
                'to.required' => 'The To field is required',
                'to.email' => 'The To field must be a valid email address',
                'to.email.dns' => 'adfds1234',
                'subject.required' => 'The Subject field is required',
                'message.required' => 'The Message field is required',
                'message.min' => 'The Message field must be at least 10 characters',
            ]
       );

        $validator = Validator::make($request->all(), $array['rules'], $array['messages']);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        } else {
            $details['email'] = $request->to;
            $details['subject'] = $request->subject;
            $details['message'] = $request->message;
            dispatch(new SendMailJob($details));
            return response()->json(['message' => 'Mail sent'], 201);
        }
    }

}
