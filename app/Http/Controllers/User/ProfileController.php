<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserRepository::findOrFail($id);
        return view('user.profile.index',['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function postUpload()
    {
        $folderPath = 'downloads/photo/image';
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0755, true); 
        }
        $file = Input::file('file');
        $filename = '';
        if(null != $file){
            $filename = $file->getClientOriginalName();
            $upload_success = Input::file('file')->move($folderPath, $filename);
        } 
    }

}
