<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Ethnic;
use Illuminate\Http\Request;

class EthnicController extends Controller
{
    // index controller
    public function index()
    {
        // get all ethnic
        $ethnic = Ethnic::all();
        // return response
        return view('pages.simrs.master-data.setup.ethnics.index',[
            'ethnics' => $ethnic,
        ]);
    }

    // create controller
    public function create(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required',
        ]);
        // create ethnic
        $ethnic = Ethnic::create([
            'name' => $request->name,
        ]);
        // return response
        return response()->json([
            'status' => 'success',
            'message' => 'Ethnic created successfully',
            'data' => $ethnic,
        ],
        201);
    }

    // update controller
    public function update(Request $request, $id)
    {
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // find ethnic
        $ethnic = Ethnic::find($id);
        // check if ethnic
        if (!$ethnic) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ethnic not found',
            ],
            404);
        }
        // update ethnic
        $ethnic->update([
            'name' => $request->name,
        ]);
        // return response
        return response()->json([
            'status' => 'success',
            'message' => 'Ethnic updated successfully',
            'data' => $ethnic,
        ],
        200);
    }

    // delete ethnic
    public function destroy(Ethnic $ethnic)
    {
        // delete ethnic
        $ethnic->delete();
        // return response
        return response()->json([
            'status' => 'success',
            'message' => 'Ethnic deleted successfully',
        ],
        200
        );
    }

    // get all ethnic
    public function getAll()
    {
        // get all ethnic
        $ethnic = Ethnic::all();
        // return response
        return response()->json([
            'status' => 'success',
            'message' => 'Ethnic retrieved successfully',
            'data' => $ethnic,
            ],
            200
        );
        // return response
    }

    // get ethnic by id
    public function getById($id)
    {
        // get ethnic by id
        $ethnic = Ethnic::find($id);
        // return response
        return response()->json([
            'status' => 'success',
            'message' => 'Ethnic retrieved successfully',
            'data' => $ethnic,
            ],
            200
        );
        // return response
    }
}
