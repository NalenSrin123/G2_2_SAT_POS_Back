<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Table::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $table = Table::create([
             'table_number' => $request->table_number,
             'qr_code' => $request->qr_code,
             'status' => $request->status,
        ]);

        return response()->json($table, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Table::findOrfail($id));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $table = Table::findOrFail($id);

         $table->update([
            'table_number' => $request->Table_number,
            'qr_code' => $request->qr_code,
            'state' => $request->state,
         ]);

         return response()->json($table);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Table::findOrFaid($id)->delete();

        return response()->json([
           'message' => 'Deleted successfully'
        ]);
    }
}
