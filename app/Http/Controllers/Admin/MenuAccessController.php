<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuAccess;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MenuAccessController extends Controller {
    public function index() {
        return view('admin.settings.menuaccess.index');
    }

    public function list() {
        return Datatables::of(Role::all())->addIndexColumn()
            ->addColumn('action', function ($model) {
                $action = "";
                $action .= "<a onclick='accessModal($model->id)' class='btn btn-sm btn-icon btn-success btn-hover-rise me-1'><i class='far fa-lightbulb text-white'></i></a>";
                $action .= " <a onclick='permissionModal($model->id)' class='btn btn-sm btn-icon btn-info btn-hover-rise me-1'><i class='bi bi-vector-pen'></i></a>";
                $action .= " <a onclick='deleteModal($model->id)' class='btn btn-sm btn-icon btn-danger btn-hover-rise me-1'><i class='bi bi-trash'></i></a>";

                return $action;
            })->rawColumns(['action'])->make(true);
    }

    public function access(Request $request) {
        return view('admin.settings.menuaccess.access', ['id' => $request->id]);
    }

    public function listaccess(Request $request) {
        $id = $request->id;
        return Datatables::of(Menu::all())->addIndexColumn()
            ->addColumn('access', function ($model) use ($id) {
                $available = MenuAccess::where(['menu_id' => $model->id, 'role_id' => $id])->first();
                $check = '';
                if ($available) {
                    $check = 'checked';
                }
                return "<div class='form-check form-check-custom form-check-solid'>
            <input class='form-check-input' type='checkbox' $check onchange='changeAccess($model->id, $id)' /></div>";
            })->rawColumns(['access'])->make(true);
    }

    public function changeaccess(Request $request) {
        $available = MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->first();

        if ($available) {
            MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->delete();
        } else {
            MenuAccess::create(['menu_id' => $request->menu_id, 'role_id' => $request->role_id]);
        }

        session()->forget('menu');
        return response()->json(['success' => 'Access Changed']);
    }

    public function permission(Request $request) {
        return view('admin.settings.menuaccess.permission', ['id' => $request->id]);
    }

    public function listpermission(Request $request) {
        $id = $request->id;
        return Datatables::of(Menu::all())->addIndexColumn()
            ->addColumn('created', function ($model) use ($id) {
                $available = MenuAccess::where(['menu_id' => $model->id, 'role_id' => $id])->first();
                $check = '';
                if ($available) {
                    if ($available->created == 1) {
                        $check = 'checked';
                    }
                    return "<div class='form-check form-check-custom form-check-solid'><input class='form-check-input' type='checkbox' $check onchange='changePermission($model->id, $id,".'"created"'.")' /></div>";
                }
            })->addColumn('updated', function ($model) use ($id) {
                $available = MenuAccess::where(['menu_id' => $model->id, 'role_id' => $id])->first();
                $check = '';
                if ($available) {
                    if ($available->updated == 1) {
                        $check = 'checked';
                    }
                    return "<div class='form-check form-check-custom form-check-solid'><input class='form-check-input' type='checkbox' $check onchange='changePermission($model->id, $id,".'"updated"'.")' /></div>";
                }
            })->addColumn('deleted', function ($model) use ($id) {
                $available = MenuAccess::where(['menu_id' => $model->id, 'role_id' => $id])->first();
                $check = '';
                if ($available) {
                    if ($available->deleted == 1) {
                        $check = 'checked';
                    }
                    return "<div class='form-check form-check-custom form-check-solid'><input class='form-check-input' type='checkbox' $check onchange='changePermission($model->id, $id,".'"deleted"'.")' /></div>";
                }
            })->addColumn('approved', function ($model) use ($id) {
                $available = MenuAccess::where(['menu_id' => $model->id, 'role_id' => $id])->first();
                $check = '';
                if ($available) {
                    if ($available->approved == 1) {
                        $check = 'checked';
                    }
                    return "<div class='form-check form-check-custom form-check-solid'><input class='form-check-input' type='checkbox' $check onchange='changePermission($model->id, $id,".'"approved"'.")' /></div>";
                }
            })->rawColumns(['approved','created','updated','deleted'])->make(true);
    }

    public function changepermission(Request $request){
        $available = MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->first();

        if ($available) {
            if($request->coloumn == 'approved'){
                if($available->approved == 1){
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['approved' => 0]);
                }else{
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['approved' => 1]);
                }
            }
            if($request->coloumn == 'created'){
                if($available->created == 1){
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['created' => 0]);
                }else{
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['created' => 1]);
                }
            }

            if($request->coloumn == 'updated'){
                if($available->updated == 1){
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['updated' => 0]);
                }else{
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['updated' => 1]);
                }
            }
            if($request->coloumn == 'deleted'){
                if($available->deleted == 1){
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['deleted' => 0]);
                }else{
                    MenuAccess::where(['menu_id' => $request->menu_id, 'role_id' => $request->role_id])->update(['deleted' => 1]);
                }
            }
        } else {

            if($request->coloumn == 'approved'){
                    MenuAccess::create(['menu_id' => $request->menu_id, 'role_id' => $request->role_id, 'approved' => 1]);

            }
            if($request->coloumn == 'created'){
                    MenuAccess::create(['menu_id' => $request->menu_id, 'role_id' => $request->role_id, 'created' => 1]);
            }

            if($request->coloumn == 'updated'){
                    MenuAccess::create(['menu_id' => $request->menu_id, 'role_id' => $request->role_id, 'updated' => 1]);
            }
            if($request->coloumn == 'deleted'){
                    MenuAccess::create(['menu_id' => $request->menu_id, 'role_id' => $request->role_id, 'deleted' => 1]);
            }
        }

        return response()->json(['success' => 'Access Changed']);
    }
}
