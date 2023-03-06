<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(): JsonResponse
    {
        //

        $customers = User::where("is_admin", 0)->get();

        return response()->json([
            'customers' => $customers
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required'],
            ]);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false,
            ]);
            return response()->json([
                'status' => true,
                'message' => "New customer created!"
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $customer = User::where('is_admin', false)->find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => "Customer not found!"
            ], 404);
        }

        return response()->json([
            'status' => true,
            'customer' => $customer
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => ['string', 'max:255'],
                'email' => ['string', 'email', 'max:255', 'unique:' . User::class],
            ]);

            $customer = User::where('is_admin', false)->find($id);

            if (!$customer) {
                return response()->json([
                    'status' => false,
                    'message' => "Customer not found!"
                ], 404);
            }

            $params = $request->all();

            // update the password hash
            if (isset($params['password'])) {
                $params['password'] = Hash::make($params['password']);
            }

            $customer->update($params);

            return response()->json([
                'status' => true,
                'message' => "Customer Updated!",
                'customer' => $customer
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $customer = User::where('is_admin', false)->find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => "Customer not found!"
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'status' => true,
            'message' => "Customer deleted successfully!"
        ]);

    }
}