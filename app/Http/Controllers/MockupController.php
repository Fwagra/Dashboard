<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreMockupRequest;
use App\Http\Controllers\Controller;
use App\Project;
use App\Mockup;
use App\MockupCategory;
use File;
use Response;
use Session;
use View;

class MockupController extends Controller
{
    /**
     * Define the mockup formats
     */
    protected $formats = [
      'desktop' => 'mockup.desktop_format',
      'tablet' => 'mockup.tablet_format',
      'mobile' => 'mockup.mobile_format',
    ];

    /* Define pathes for image saving */
    protected $destinationImages = 'uploads/mockups/';
    protected $destinationPsd = 'uploads/psd/';

    /**
     * Construct method
     */
    public function __construct() {
      $this->middleware('guest.auth', ['only' => ['index', 'show']]);
      $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @param Mockup $mockup
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project, Mockup $mockup)
    {
      $categories = $project->mockupCategories->lists('name','id');
      $formats = $this->formats;
      return View::make('mockups.new', compact('project', 'categories', 'formats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreMockupRequest  $request
     * @param Project $project
     * @param Mockup $mockup
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMockupRequest $request, Project $project, Mockup $mockup)
    {
        $request->merge([
            'project_id' => $project->id
        ]);

        $fields = $request->all();
        // Eventually generate a new category
        if(!MockupCategory::find($request->mockup_category_id))
        {
          $category = $this->findOrCreateCategory($request->mockup_category_id, $project);
          $fields['mockup_category_id'] = $category->id;
        }

        if($request->hasFile('images')){
            $image = $request->file('images');
            $filename = $this->saveImage($project, $image, $this->destinationImages);
            $fields['images'] = $filename;
        }

        if($request->hasFile('psd')){
            $image = $request->file('psd');
            $filename = $this->saveImage($project, $image, $this->destinationPsd);
            $fields['psd'] = $filename;
        }
        $mockup = Mockup::create($fields);

        Session::flash('message', trans('mockup.success_create'));
        return redirect()->action('MockupController@index', ['projectSlug' => $project->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Project $project
     * @param  Mockup $mockup
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Mockup $mockup)
    {
        $categories = $project->mockupCategories->lists('name','id');
        $formats = $this->formats;
        return View::make('mockups.edit', compact('project', 'mockup', 'categories', 'formats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreMockupRequest  $request
     * @param  Project $project
     * @param  Mockup $mockup
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMockupRequest $request, Project $project, Mockup $mockup)
    {
        // Require image if the current mockup image is not set
        if(empty($mockup->images)){
            $this->validate($request, [
                'images' => 'required',
            ]);
        }

        $fields = $request->all();

        // Eventually generate a new category
        if(!MockupCategory::find($request->mockup_category_id))
        {
          $category = $this->findOrCreateCategory($request->mockup_category_id, $project);
          $fields['mockup_category_id'] = $category->id;
        }

        if($request->hasFile('images')){
            $image = $request->file('images');
            $filename = $this->saveImage($project, $image, $this->destinationImages);
            $fields['images'] = $filename;
        }

        if($request->hasFile('psd')){
            $image = $request->file('psd');
            $filename = $this->saveImage($project, $image, $this->destinationPsd);
            $fields['psd'] = $filename;
        }

        $mockup->update($fields);

        Session::flash('message', trans('mockup.success_edit'));
        return redirect()->action('MockupController@index', ['projectSlug' => $project->slug]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Remove an image
     * @param  \Illuminate\Http\Request  $request
     * @param Project $project
     * @param Mockup $mockup
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request $request, Project $project, Mockup $mockup, $type)
    {
        $return  = false;

        if(isset($mockup->$type)){
            $path = 'destination'.ucfirst($type);
            $path = $this->$path;
            $this->deleteFileImage($path.$mockup->$type);
            $mockup->$type = '';
            $mockup->save();
            $return  = $type;
        }

        return Response::json($return);
    }

    /**
     * Delete the provided image file
     * @param string $filename
     */
    public function deleteFileImage($filepath)
    {
        if(File::isFile($filepath))
            File::delete($filepath);
    }

    /**
     * Find or create a mockup category from the provided name
     * @param string $name
     * @param Project $project
     * @return MockupCategory $category
     */
    private function findOrCreateCategory($name, Project $project)
    {
      if(!$category = MockupCategory::where('name', $name)->first()){
        $category = new MockupCategory();
        $category->name = $name;
        $category->project_id = $project->id;
        $category->order = 0;
        $category->save();
      }

      return $category;
    }

    /**
     * Save an image
     * @param Project $project
     * @param object $image
     * @param string $path
     * @return string $filename
     */
    public function saveImage(Project $project, $image, $path)
    {
        if(!File::isDirectory($path))
            File::makeDirectory($path,  0775, true);

        $filename = $project->slug . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($path, $filename);

        return $filename;
    }
}
