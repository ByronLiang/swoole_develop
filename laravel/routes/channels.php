<?php

use App\Models\Author;

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

 Broadcast::channel('chatroom{authorId}', function ($user, $authorId) {
     $author = Author::with('room')->findorFail($authorId);
     if ('pay_user' == $author->room->listener) {
         return false;
     } else {
         return $user;
     }
 });

 Broadcast::channel('private_message_user_{id}', function ($user, $id) {
     return (int) $user->id === (int) $id;
 });

//  Broadcast::channel('private_message_admin_{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
