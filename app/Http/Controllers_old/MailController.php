<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use App\Mail\SampleMail; // Import your custom email class


class MailController extends Controller
{
    //
    public function sendEmail($to, $name, $password)
    {
        try {
            $data = [
                'user_name' => $name,
                'email' => $to,
                'password' => $password,
                'url' => 'https://visadone.com/laravel_demo/laravel8/public/login',
            ];

            Mail::send('mails.user_created', $data, function ($message) use ($to) {
                $message->to($to, 'VISADONE')
                    ->subject('User Created')
                    ->from('tech@satgurutravel.com', 'VISADONE');
            });

            return 'User Created';
        } catch (\Exception $e) {
            $data = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function sendEmail_agent($to, $name, $password)
    {
        try {
            $data = [
                'user_name' => $name,
                'email' => $to,
                'password' => $password,
                'url' => 'https://visadone.com/laravel_demo/laravel8/public/login',
            ];

            Mail::send('mails.agent_created', $data, function ($message) use ($to) {
                $message->to($to, 'VISADONE')
                    ->subject('Agent Created')
                    ->from('tech@satgurutravel.com', 'VISADONE');
            });

            return 'Agent Created';
        } catch (\Exception $e) {
            $data = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function sendEmail_agency($to, $name, $password)
    {
        try {
            $data = [
                'user_name' => $name,
                'email' => $to,
                'password' => $password,
                'url' => 'https://visadone.com/laravel_demo/laravel8/public/login',
            ];

            Mail::send('mails.agency', $data, function ($message) use ($to) {
                $message->to($to, 'VISADONE')
                    ->subject('Agency Created')
                    ->from('tech@satgurutravel.com', 'VISADONE');
            });

            return 'Agent Created';
        } catch (\Exception $e) {
            $data = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function sendEmail_application($to, $name, $visa_id,$status)
    {

        try {
            $data = [
                'name' => $name,
                'email' => $to,
                'id' => $visa_id,
                'status'=> $status,
                'url' => 'https://visadone.com/laravel_demo/laravel8/public/login',
            ];

            Mail::send('mails.status_branch', $data, function ($message) use ($to) {
                $message->to($to, 'VISADONE')
                    ->subject('Application Status Update')
                    ->from('tech@satgurutravel.com', 'VISADONE');
            });

            return 'Agent Created';
        } catch (\Exception $e) {
            dd($e);
            $data = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
