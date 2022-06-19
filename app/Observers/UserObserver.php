<?php

namespace App\Observers;

use App\Jobs\EmailVerfication;
use App\Mail\UserSubscribed;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {



        // $job =  (new  EmailVerfication)->delay(Carbon::now()->addSecond(5));
        // dispatch($job);

        // $code = $user->id . '-' . rand(1000, 100000);
        $code =  rand(1000, 1000000);
        // dd($user->email);
        // Mail::to($user->email)->send(new UserSubscribed($code));
        Mail::to($user->email)->send(new UserSubscribed($code));
        $updateUser = User::find($user->id);
        $updateUser->ver_code =  $code;
        $updateUser->save();

        
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
