<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('pages.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:departments,name']);
        Department::create($request->only('name'));
        return redirect()->route('departments.index')->with('success', 'Отдел успешно создан');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate(['name' => 'required|unique:departments,name,' . $department->id]);
        $department->update($request->only('name'));
        return redirect()->route('departments.index')->with('success', 'Отдел успешно обновлен');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Отдел успешно удален');
    }
}
