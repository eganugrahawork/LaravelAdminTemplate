<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(){
        return view('admin.settings.role.index');
    }

    public function list(){
        return Datatables::of(Role::all())->addIndexColumn()
        ->addColumn('action', function($model){
            $action = "";
                    $action .= "<a onclick='editModal($model->id)' class='btn btn-sm btn-icon btn-warning btn-hover-rise me-1'><i class='bi bi-pencil-square'></i></a>";
                    $action .= " <a onclick='deleteModal($model->id)' class='btn btn-sm btn-icon btn-danger btn-hover-rise me-1'><i class='bi bi-trash'></i></a>";

            return $action;
        })->rawColumns(['action'])->make(true);
    }
    public function create(){
        return view('admin.settings.role.create');
    }
    public function store(Request $request){
        Role::create([
            'role' => $request->role
        ]);
        return response()->json(['success' => 'Role Created']);
    }

    public function edit(Request $request){

        return view('admin.settings.role.edit', ['onRole' => Role::where(['id' => $request->id])->first()]);
    }

    public function update(Request $request){
        Role::where(['id'=>$request->id])->update([
            'role' => $request->role
        ]);
        return response()->json(["success" => "Data $request->role Updated"]);
    }

    public function destroy(Request $request){
        Role::where(['id' => $request->id])->delete();
        return response()->json(["success" => "Data Deleted"]);
    }
}
