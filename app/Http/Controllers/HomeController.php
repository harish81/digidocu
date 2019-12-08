<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Document;
use App\File;
use App\Http\Requests\UpdateProfileRequest;
use App\Rules\CurrentPassword;
use App\Tag;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ZipArchive;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('auth');*/
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $documents = Document::all();
        $activities = Activity::with(['createdBy','document']);
        if($request->has('activity_range')){
            $dates = explode("to",$request->get('activity_range'));
            $activities->whereDate('created_at','>=',$dates[0]??'');
            $activities->whereDate('created_at','<=',$dates[1]??'');
        }
        $activities = $activities->orderByDesc('id')->paginate(25);
        $allTags = Tag::with('documents','documents.files')->withCount('documents');
        if(!auth()->user()->can('read documents')){
            $allPerm = auth()->user()->getAllPermissions();
            $tmpTags = array_column(groupTagsPermissions($allPerm),'tag_id');
            $allTags->whereIn('id',$tmpTags);
        }
        $allTags = $allTags->get();
        $tagCounts = $allTags->count();
        $allDocs =  Document::whereHas('tags',function ($q) use($allTags){
            return $q->whereIn('tag_id',$allTags->pluck('id')->toArray());
        })->pluck('id');
        $documentCounts = $allDocs->count();
        $filesCounts = File::whereIn('document_id',$allDocs->toArray())->count();
        return view('home',compact('documents','activities','tagCounts','documentCounts','filesCounts'));
    }

    public function welcome()
    {
        \Artisan::call("inspire");
        $quotes = \Artisan::output();
        return view('welcome',compact('quotes'));
    }

    public function profile(UpdateProfileRequest $request)
    {
        $profile = User::findOrFail(\Auth::id());
        $data = $request->all();
        if($request->isMethod('POST')){
            if($request->has('btnprofile')){
                \Flash::success("Profile Updated Successfully");
            }elseif ($request->has('btnpass')){
                $data['password'] = bcrypt($data['new_password']);
                \Flash::success('Password Updated Successfully');
            }
            $profile->update($data);
            return redirect()->route('profile.manage');
        }

        return view('profile',compact('profile'));
    }

    /**
     * Show Or Download File.
     * @param Request $request
     * @param string $dir
     * @param null $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showFile(Request $request, $dir = 'original', $file = null)
    {
        $name = $file;
        $attachment = 'inline';
        if($request->has('force')){//for force download
            $attachment = 'attachment';
        }
        if (!empty($file)) {
            $fileModels = File::where('file', $file)->get();
            if ($fileModels->isNotEmpty()) {
                $fileModel = $fileModels[0];
                $name = Str::slug($fileModel->document->name). "_" .$fileModel->document->id . "_" . $dir . "_" . Str::slug($fileModel->name);
                $name .= "." . last(explode('.', $file));
            }
        }
        $file = storage_path('app/files/' . $dir . '/') . $file;
        return response()->file($file, ['Content-disposition' => $attachment.'; filename="' . $name . '"']);
    }

    public function downloadZip(Request $request, $id, $dir = 'all')
    {
        $document = Document::findOrFail($id);
        $tmpDir = storage_path('app/tmp/');
        if(!file_exists($tmpDir)){
            mkdir($tmpDir,0755,true);
        }
        $docFileTitle = Str::slug($document->name)."_".Str::slug($dir)."_".$document->id.".zip";
        $zip_file = $tmpDir.$docFileTitle;

        $directories = [];
        $imageVariants = explode(",",config('settings.image_files_resize'));
        if($dir=='all' || $dir=='original'){
            $directories[] = "original";
        }else{
            $directories[] = $dir;
        }
        if($dir=='all'){
            foreach ($imageVariants as $imageVariant) {
                $directories[] = $imageVariant;
            }
        }

        /*Create a zip archive*/
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if(!empty($dir) && !empty($directories)){
            foreach ($directories as $directory) {
                foreach ($document->files as $file) {
                    $newName = $directory."/".Str::slug($file->name). "_" .$file->id;
                    $newName .= "." . last(explode('.', $file->file));
                    $existingFile = storage_path("app/files/$directory/$file->file");
                    if(file_exists($existingFile)) {
                        $zip->addFile($existingFile, $newName);
                    }
                }
            }
        }

        $zip->close();
        return response()->download($zip_file)->deleteFileAfterSend();
    }

    public function downloadPdf(Request $request)
    {
        $files = $request->get('images','');
        $varient = $request->get('images_varient','original');
        if(empty($files)){
            return redirect()->back();
        }
        $files = explode(",",$files);
        $docName = Document::whereHas('files',function ($q) use ($files){
            return $q->where('file',$files[0]);
        })->pluck('name')->first();
        $docName = Str::slug($docName)."_".$varient;
        $filePaths = [];
        foreach ($files as $file) {
            $filePaths[] = Image::make(storage_path("app/files/$varient/$file"))->encode('data-url');
        }
        $pdf = PDF::loadView('pdf', compact('docName','filePaths'));
        return $pdf->download($docName.".pdf");
    }
}
