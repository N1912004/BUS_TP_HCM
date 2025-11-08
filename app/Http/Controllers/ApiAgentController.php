<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiAgentController extends Controller
{
    public function handleApiRequest(Request $request)
    {
        $subject = $request->input('subject');

        // Handle the API request here, using the selected subject
        return response()->json(['message' => 'API request received', 'subject' => $subject]);
    }
}