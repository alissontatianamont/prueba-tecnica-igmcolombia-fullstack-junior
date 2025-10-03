<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{

    public function __construct(private UserService $userService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $perPage = request()->get('per_page', 15);
            $filters = request()->only([
                'name', 'email', 'role', 'sort_by', 'sort_direction'
            ]);

            if (request()->has('paginate') && request()->get('paginate') === 'false') {
                $users = $this->userService->getAll();
                return response()->json([
                    'data' => $users,
                    'message' => 'Users retrieved successfully'
                ], 200);
            }

            $users = $this->userService->getPaginated($filters, $perPage);
            return response()->json([
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ],
                'filters' => $filters,
                'message' => 'Users retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Users could not be retrieved',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
       try {
           
           $user = $this->userService->store($request->validated());
           return response()->json($user, 201);
       } catch (\Throwable $th) {
           \Log::error('Error al crear usuario:', ['error' => $th->getMessage()]);
           return response()->json(['error' => 'User could not be created', 'details' => $th->getMessage()], 500);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->findById($id);
            return response()->json([
                'data' => $user,
                'message' => 'User retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'User not found',
                'message' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = $this->userService->update($id, $request->validated());
            return response()->json([
                'data' => $user,
                'message' => 'User updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'User could not be updated',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->delete($id);
            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            if ($e->getCode() == 422) {
                return response()->json([
                    'error' => 'User cannot be deleted',
                    'message' => $e->getMessage()
                ], 422);
            }
            return response()->json([
                'error' => 'User could not be deleted',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'User could not be deleted',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
