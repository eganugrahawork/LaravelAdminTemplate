<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\MenuAccess;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        Gate::define('approved', function (User $user,  $url) {
            $idnya = Menu::where(['url' => $url])->pluck('id')->first();

            $isAvailable = null;
            if ($idnya) {
                $isAvailable = MenuAccess::where(['menu_id' => $idnya, 'role_id' => $user->role_id, 'approved' => 1])->first();
            }
            return $isAvailable;
        });
        Gate::define('created', function (User $user,  $url) {
            $idnya = Menu::where(['url' => $url])->pluck('id')->first();

            $isAvailable = null;
            if ($idnya) {
                $isAvailable = MenuAccess::where(['menu_id' => $idnya, 'role_id' => $user->role_id, 'created' => 1])->first();
            }
            return $isAvailable;
        });
        Gate::define('updated', function (User $user,  $url) {
            $idnya = Menu::where(['url' => $url])->pluck('id')->first();

            $isAvailable = null;
            if ($idnya) {
                $isAvailable = MenuAccess::where(['menu_id' => $idnya, 'role_id' => $user->role_id, 'updated' => 1])->first();
            }
            return $isAvailable;
        });
        Gate::define('deleted', function (User $user,  $url) {
            $idnya = Menu::where(['url' => $url])->pluck('id')->first();

            $isAvailable = null;
            if ($idnya) {
                $isAvailable = MenuAccess::where(['menu_id' => $idnya, 'role_id' => $user->role_id, 'deleted' => 1])->first();
            }
            return $isAvailable;
        });
    }
}
