<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use App\DataTables\CustomersDataTable;
use App\Http\Requests\CustomerPostRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CustomersDataTable $dataTable)
    {
        return $dataTable->render('pages.customer.index', ['type_menu' => 'customer']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('pages.customer.create', ['type_menu' => 'customer']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerPostRequest $request)
    {
        $validated = $request->validated();

        $customer = new Customer();
        $customer->fullname = $request->fullname;
        $customer->address = $request->address;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->password = Hash::make($request->password);
        $customer->save();
        $notification = [
            'message' => 'Data Berhasil Dibuat',
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return view('pages.customer.show', ['type_menu' => 'customer', 'customer' => $customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $customer->fullname = $request->fullname;
        $customer->address = $request->address;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        if ($request->password)
            $customer->password = Hash::make($request->password);
        $customer->save();

        $notification = [
            'message' => 'Data Berhasil Diubah',
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        $notification = [
            'message' => 'Data Berhasil Dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.index')->with($notification);
    }
}
