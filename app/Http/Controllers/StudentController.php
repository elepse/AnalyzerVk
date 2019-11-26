<?php

namespace App\Http\Controllers;

use App\Group;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function group() {
        $groups = Group::all();
        return response()->json(
            [
                'status' => 'success',
                'groups' => $groups->toArray()
            ], 200);
    }

    public function students($id) {
        if ($id !== null) {
            $students = Student::query()->where('group_id', '=', $id)->get();
            $group = Group::find($id);
           return response()->json([
               'status' => 'success',
               'students' => $students->toArray(),
               'group' => $group->toArray()
           ]);
        }else {
            return response()->json(
                [
                    'status' => 'error',
                    'error' => 'id = null'
                ], 401);
        }
    }

    public function edit(Request $request) {
        Student::query()->find($request->get('id_student'))->update([
            'name' => $request->get('flo'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'vk_link' => $request->get('vkLink')
        ]);
        return response()->json([
           'status' => 'success'
        ],200);
    }


}
