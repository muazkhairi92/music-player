<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\App\Http\Requests\RegisterRequest;
use Modules\User\App\Http\Requests\UpdateUserRequest;
use Modules\User\App\Http\Resources\UserResource;
use Modules\User\App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $perPage = request('per_page') ?? 15;
        $search = request('search');

        $users = User::where('id', '!=', $user->id)
            ->when(request('search'), function ($q) use ($search) {
                $q->where(function ($qu) use ($search) {
                    $qu->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
                });
            })
            ->paginate($perPage);

        return $this->sendResponse('users list', UserResource::collection($users)->response()->getData(), 200);
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create($request->all());
        $user->refresh();
        $user->subscription()->create();

        return $this->sendResponse('User creation successful.', new UserResource($user), 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update($request->all());

            return $this->sendResponse('User updated successfully', new UserResource($user), 200);
        }

        return $this->sendError('Failed too update user', null, 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = Auth::user();

        $user->delete();

        return $this->sendResponse('successfully deleted user', null, 200);
    }
}
