<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userPosts = [];
 
        if ($request->session()->has('access_token')) {
            $resourceResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '. $request->session()->get('access_token')
            ])->get(env('RESOURCE_APP_URL') . 'api/user/resource/posts');
           
            if($resourceResponse->status() === 200) {
                $userPosts = $resourceResponse->json();
            }
        }

        return view('welcome', compact('userPosts'));
    }


    public function requestToken(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $response = Http::post(env('RESOURCE_APP_URL') . 'oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('CLIENT_ID'),
            'client_secret' =>  env('CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);

        $response = json_decode((string) $response->getBody());

        if(isset($response->access_token)) {

            // Normally, if this was a desktop app or mobile app or something similar, we would store somewhere on the devic.
            // But I will store in a session for now. Just for demonstration purposes
 
            $request->session()->put('access_token', $response->access_token);
            $request->session()->put('expires_in', $response->expires_in); 
        }

        return redirect('/');
    }
}
