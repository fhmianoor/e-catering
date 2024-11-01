<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::get();

        if($menus->count() > 0){
            return MenuResource::collection($menus);
        }else{
            return response()->json(['message' => 'No record available'], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'all fields are mandetory',
                'error' => $validator->messages()
            ], 402);
        }

        $menu = Menu::create([
            'name' => $request -> name,
            'image' => $request -> image,
            'price' => $request -> price
        ]);
        return response()->json([
            'message' => 'Created Menu Successfully',
            'data' => new MenuResource($menu)
        ], 200);
    }

    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'all fields are mandetory',
                'error' => $validator->messages()
            ], 402);
        }

        $menu -> update([
            'name' => $request -> name,
            'image' => $request -> image,
            'price' => $request -> price
        ]);
        return response()->json([
            'message' => 'Update Menu Successfully',
            'data' => new MenuResource($menu)
        ], 200);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json([
            "message" => "Delete Menu Sucessfully"
        ], 200);
    }

}
