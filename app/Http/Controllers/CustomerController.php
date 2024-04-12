<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportCustomerRequest;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::orderByDesc('updated_at')->paginate(5);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());
        return redirect()->route('customers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->route('customers.edit', $customer)->with('status', 'customer-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index');
    }

    public function import()
    {
        return view('customers.import');
    }

    public function storeImport(StoreImportCustomerRequest $request)
    {
        $customers = [];
        $row = 1;
        try {
            $open = fopen($request->file('file')->getPathname(), 'r');
            while (($data = fgetcsv($open, 1000, ',')) !== FALSE) {
                if ($row === 1) {
                    $row++;
                    continue;
                }
                $customers[] = $data[0];
            }
        } finally {
            fclose($open);
        }
        DB::table('customers')->insertOrIgnore(
            collect($customers)
                ->map(function ($value) {
                    return [
                        'name' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })
                ->toArray()
        );
        return redirect()->route('customers.index');
    }
}
