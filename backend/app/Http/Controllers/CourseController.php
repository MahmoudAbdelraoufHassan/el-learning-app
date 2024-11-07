<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller 
{
    


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $courses = Course::with('user:id,name,email')->get();
        // return $courses;
        return CourseResource::collection(Course::all());
        
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' =>'required|max:255',
            'description' =>'required'
        ]);
        
        $course = $request->user()->courses()->create($fields);
        
        return [ 'Done' => 'Course Created Successfully' , 'course' => $course ];
    }



    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        
        return new CourseResource($course);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        Gate::authorize('modify' , $course);
        $post = $request->validate([
            'title' =>'required|max:255',
            'description' =>'required'
        ]);
        $course->update($post);
        return ['Done' => 'Course Updated Successfully', 'course' => $course];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        Gate::authorize('modify' , $course);
        $course->delete();
        return ['Done' => 'Course Deleted Successfully'];
    }
}
