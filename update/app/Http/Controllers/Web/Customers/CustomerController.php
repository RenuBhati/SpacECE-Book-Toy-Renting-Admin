<?php

namespace App\Http\Controllers\Web\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = (new CustomerRepository())->getAllOrFindBySearch();

        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        return view('customers.show', [
            'customer' => $customer,
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(RegistrationRequest $request)
    {
        $user = (new UserRepository())->registerUser($request);
        (new CustomerRepository())->storeByUser($user);
        $user->assignRole('customer');
        $user->update([
            'mobile_verified_at' => now(),
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer create successfully');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'mobile' => 'required|numeric|unique:users,mobile,'.$customer->user->id,
            'email' => 'required|unique:users,email,'.$customer->user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);
        (new UserRepository())->updateProfileByRequest($request, $customer->user);

        return redirect()->route('customer.index')->with('success', 'Customer Update successfully');
    }

    public function delete(Customer $customer)
    {
        $user = $customer->user;
        $orders = $customer->orders;

        foreach ($orders as $order) {
            $order->payment?->delete();
            $order->products()->detach();
            $order->rating?->delete();
            $order->additionals()?->detach();
            $order->delete();
        }
        $customer->devices()?->delete();
        $customer->addresses()?->delete();

        $customer->cards()?->delete();

        $customer->notifications()?->delete();

        $customer->delete();

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }

    public function changePassword(Customer $customer)
    {
        return view('customers.change-password', compact('customer'));
    }

    public function updatePassword(Customer $customer, ChangePasswordRequest $request)
    {
        if (! Hash::check($request->current_password, $customer->user->password)) {
            return back()->with('error', 'You have entered wrong password');
        }
        $customer->user->update([
            'password' => Hash::make($request->password),
        ]);

        return to_route('customer.index')->with('success', 'password change successfully');
    }
}
