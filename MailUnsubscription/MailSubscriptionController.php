<?php

namespace App\Http\Controllers;

use App\Events\UserUnsubscribed;
use App\User;
use Illuminate\Http\Request;

class MailSubscriptionController extends Controller
{
    /**
     * Unsubscribe a user given by Mailgun request
     * @return \Illuminate\Http\JsonResponse
     */

    static function APIunsubscribeUser(Request $request)
    {
        // Get email for unsubscription
        $unsubEmail = $request->input('event-data')['recipient'];
        $unsubReason = $request->input('event-data')['event'];

        // No user in request
        if(!$unsubEmail) {
            $response['output'] = 'No data in request';
            return response()->json($response, 406);
        }

        // Given user doesn't exist
        if(!$unsubUser = User::whereEmail($unsubEmail)->first()) {
            $response['output'] = 'User not found';
            return response()->json($response, 404);
        }

        // User already unsubscribed
        if ($unsubUser->unsubscribed == 1) {
            $response['output'] = 'User already unsubscribed';
            return response()->json($response, 402);
        }

        // Change value in DB
        $unsubUser->unsubscribed = 1;
        $unsubUser->save();

        // Notify listeners
        event(new UserUnsubscribed($unsubUser, $unsubReason));

        // Return successful response
        $response['output'] = 'User unsubscribed';
        return response()->json($response, 200);
    }
}
