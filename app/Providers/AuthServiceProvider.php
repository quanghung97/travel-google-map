<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Trip;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use App\Policies\ProfilePolicy;
use App\Policies\TripPolicy;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => ProfilePolicy::class,
        Trip::class => TripPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
