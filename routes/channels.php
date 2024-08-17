<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('User.{id}', function () {
    return true;
});

Broadcast::channel('Test', function () {
    return true;
});
