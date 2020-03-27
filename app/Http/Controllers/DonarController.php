<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donar;

class DonarController extends Controller
{
    public function index()
    {
        return response([
            'status_code' => 200,
            'donars' => Donar::all()
        ]);
    }

    public function show(Request $request, $id)
    {
        return response([
            'status_code' => 200,
            'donar' => Donar::find($id)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'age' => 'required|numeric',
            'blood_group' => 'required|string',
            'weight' => 'required|numeric',
            'contact_number' => 'required|numeric',
            'home_town' => 'required|string',
            'district' => 'required|string',
        ]);

        Donar::create($validated);

        return response([
            'status_code' => 200,
            'msg' => 'Donar details added successfully'
        ]);
    }

    public function setLastDonationDate(Request $request)
    {
        $validated = $request->validate([
            'donar_id' => 'required',
            'donation_date' => 'required'
        ]);

        Donar::where('id', $validated['donar_id'])->update([
            'last_donatied_at' => $validated['donation_date']
        ]);

        return response([
            'status_code' => 200,
            'msg' => 'Donation date updated successfully'
        ]);

    }


    public function delete(Request $request)
    {
        $validated = $request->validate([
            'donar_id' => 'required',
        ]);

        Donar::where('id', $validated['donar_id'])->delete();

        return response([
            'status_code' => 200,
            'msg' => 'Donar deleted successfully'
        ]);

    }


}
