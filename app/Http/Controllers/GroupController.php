<?php

namespace App\Http\Controllers;

use App\Group;
use App\Student;
use App\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function groups() {
        $curators = User::all();
        return response()->json([
           'status' => 'success',
           'curators' => $curators->toArray()
        ],200);
    }

    public function create(Request $request) {
        $name = $request->get('name_group', null);
        $curator = $request->get('curator', null);
        $year = $request->get('year', null);


        (new Group())->fill([
           'name_group' => $name,
           'curator' => $curator,
            'year' => $year
        ])->save();

        return response()->json([
           'status' => 'success'
        ], 200);
    }

    public function delete(Request $request) {
        $idGroup = $request->get('idGroup');
        Student::query()->where('group_id', '=', "$idGroup")->delete();
        Group::find($idGroup)->delete();

        return response()->json([
           'status' => 'success'
        ], 200);
    }

}
