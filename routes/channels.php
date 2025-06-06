<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return true; // Allow everyone to subscribe to any channel
});

Broadcast::channel('chats', function ($user = null, $chatId = null) {
    return true; // Allow everyone to subscribe to any channel
});

Broadcast::channel('offers.{chatId}', function ($user = null , $chatId = null) {
    return true; 
});
