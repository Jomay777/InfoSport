<?php

namespace App\Http\Middleware;

//use App\Http\Resources\UserResource;
use App\Http\Resources\UserSharedResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth.user' => fn () => $request->user()
                ? new UserSharedResource($request->user())
                : null,
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'user.roles' => $request->user() ? $request->user()->roles->pluck('name'): [],
            'user.permissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name'): [],
        ];
    }
}
