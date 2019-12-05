<?php

namespace App\Http\Controllers;

use App\Group;
use App\Student;
use App\VkPost;
use Illuminate\Http\Request;
use function Composer\Autoload\includeFile;

class StudentController extends Controller
{
    public function group()
    {
        $groups = Group::all();
        return response()->json(
            [
                'status' => 'success',
                'groups' => $groups->toArray()
            ], 200);
    }

    public function students($id)
    {
        if ($id !== null) {
            $students = Student::query()->where('group_id', '=', $id)->get();
            $group = Group::query()->join('users', 'users.id', '=', 'groups.curator')->find($id);
            return response()->json([
                'status' => 'success',
                'students' => $students->toArray(),
                'group' => $group->toArray()
            ]);
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'error' => 'id = null'
                ], 400);
        }
    }

    public function edit(Request $request)
    {
        Student::query()->find($request->get('id_student'))->update([
            'name' => $request->get('flo'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'vk_link' => $request->get('vkLink')
        ]);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function create(Request $request)
    {
        $name = $request->get('name', null);
        $group_id = $request->get('group_id', null);
        $phone = $request->get('phone', null);
        $address = $request->get('address');

        $student = (new Student())->fill([
            'name' => $name,
            'group_id' => $group_id,
            'phone' => $phone,
            'address' => $address,
        ]);
        $student->save();

        return response()->json([
            'status' => 'success',
            'student_id' => $student->id_student
        ], 200);
    }

    public function getPosts($id)
    {
        $idVkStudent = Student::query()->where('id_student', '=', "$id")->get('vk_id');
        $idVkStudent = $idVkStudent->get(0)->vk_id;
        $posts = VkPost::query()->with('attachments')
            ->where('id_vk_student', '=', "$idVkStudent")->get();
        return response()->json([
            'status' => 'success',
            'posts' => $posts->toArray()
        ], 200);
    }

    public function delete(Request $request)
    {
        $id = $request->get('id_student');
        Student::find($id)->delete();
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
