<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;

    protected $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keys = ['search', 'page'];
        $data = $this->userRepository->getUserFilter($request, $keys);

        return Inertia::render('User/Index', [
            'data' => $data,
            'filters' => $request->all($keys),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => $this->passwordRules(),
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        $userCreated = $this->userRepository->create($request->only([
            'name', 'email', 'password'
        ]));

        if ($request->photo) {
            $userCreated->updateProfilePhoto($request->photo);
        }

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : redirect()
                ->intended('admin/users')
                ->with('message', 'User have been created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('User/Edit', [
            'data' => $this->userRepository->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($request->photo)) {
            $user->updateProfilePhoto($request->photo);
        }

        $user->forceFill([
            'name' => $request->name,
            'email' => $request->email,
        ])->save();

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : redirect()
                ->intended('admin/users')
                ->with('message', 'User have been updated successfully!');
    }

    /**
     * Soft delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->userRepository->delete($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userRepository->delete($id);
    }

    public function editPassword($id)
    {
        return Inertia::render('User/ChangePassword', [
            'data' => $this->userRepository->find($id)
        ]);
    }

    public function changePassword(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        Validator::make($request->all(), [
            'password' => $this->passwordRules(),
        ])->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : redirect()
                ->intended('admin/users')
                ->with('message', 'User\'s password have been updated successfully!');
    }
}
