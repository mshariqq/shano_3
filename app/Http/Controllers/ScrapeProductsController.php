<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RarArchive;

class ScrapeProductsController extends Controller
{
    public $shano;
    public $product;
    // construct
    public function __construct(){
        // $this->middleware('auth');
        $this->shano = new \App\Shano();
    }
    public function index(){
        // get all scrape products
        $shano = new \App\Shano();
        $data['products'] = $shano->getScrapeProducts();
        return view('scrape.index', compact('data'));
    }

    // create
    public function create(){
        return view('scrape.create');
    }

    // store
    public function store(Request $request){
        // storing scrap product, first check if product exists with same name
        $shano = $this->shano;
        $name = $request->name;
        $new_dir = $shano->path . $shano->scrape_products."/".$name;
        if(!file_exists($new_dir)){
            // add random number to the name
            $name = $name . rand(1, 1000);
            $new_dir = $shano->path . $shano->scrape_products."/".$name;
        }

        // create the dir
        if(mkdir($new_dir)){
            // create sub directories inside this images, file, data, projects
            mkdir($new_dir."/images");
            mkdir($new_dir."/file");
            mkdir($new_dir."/data");
            mkdir($new_dir."/projects");

            // now upload the thumbnail into this directory
            $thumbnail = $request->file('thumbnail');
            $thumbnail->move($new_dir, "thumbnail.png");

            // now create a new file called product.json
            $product = array(
                "name" => $name,
                "description" => $request->description,
                "tagline" => $request->tagline,
                "link" => $request->link,
                "status" => "active",
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            );
            $product = json_encode($product);
            file_put_contents($new_dir."/product.json", $product);

            // now upload the images[] inside the images subdirectory
            $images = $request->file('images');
            foreach($images as $image){
                $image->move($new_dir."/images", $image->getClientOriginalName());
            }

            // now upload the file into the file subdirectory
            $file = $request->file('file');
            $file->move($new_dir."/file", $file->getClientOriginalName());

            // return back with success
            return back()->with('success', 'Product created successfully');
        }else{
            return redirect()->back()->with('error', 'Error creating product Directory');
        }
    }

    // show
    public function show($id){
        // new scrapePRODUCT
        $product = new \App\ScrapeProducts($id);
        return view('scrape.show', compact('product'));
    }

    // create project
    public function createProject(Request $request, $id){

        // new scrapePRODUCT
        $product = new \App\ScrapeProducts($id);

        // we need to copy the zip file inside the file subdirectory into the projects subdirectory with name of project
        $project = $request->name;
        $project_dir = $product->shano->path . $product->shano->scrape_products."/".$id."/projects/".$project;
        if(!file_exists($project_dir)){
            // create the project directory
            mkdir($project_dir);

            // check if the request base is original or other
            if($request->base == "original"){
                    // copy the zip file into this directory
                    $file = $product->shano->path . $product->shano->scrape_products."/".$id."/file/".$product->file()[2];
                    // check if the file is ZIP or RAR
                    if($product->file()[1] == "zip"){
                        copy($file, $project_dir."/".$product->file()[2]);
                        // now extract the zip file
                        $zip = new \ZipArchive;
                        $res = $zip->open($project_dir."/".$product->file()[2]);
                        if ($res === TRUE) {
                            $extract = $zip->extractTo($project_dir);
                            // check if extract is success
                            if($extract){
                                $zip->close();
                                // // now delete the zip file
                                // unlink($project_dir."/".$product->file()[2]);
                                // now create a new file called project.json
                                $project = array(
                                    "name" => $project,
                                    "description" => "Extract from " . $request->base,
                                    "status" => "active",
                                    "created_at" => date("Y-m-d H:i:s"),
                                    "updated_at" => date("Y-m-d H:i:s")
                                );
                                $project = json_encode($project);
                                file_put_contents($project_dir."/project.json", $project);
                                // return back with success
                                return back()->with('success', 'Project created successfully with ZIP file');
                            }else{
                                // revert back the project folder if error
                                rmdir($project_dir);
                                return redirect()->back()->with('error', 'Error extracting zip file');
                            }

                        } else {
                            // /revert back
                            rmdir($project_dir);
                            return redirect()->back()->with('error', 'Error opening zip file');
                        }
                    }else{
                        // // if the file is not zip, then it is RAR
                        copy($file, $project_dir."/".$product->file()[2]);
                        return redirect()->back()->with('error', 'Error opening RAR file');

                        // // now extract the RAR file
                        // $rar_file = $project_dir."/".$product->file()[2];


                        // // extract rar file with shell_exec WinRar command
                        // $command = "C:\Program Files\WinRAR\WinRAR.exe x $rar_file $project_dir";
                        // $output = shell_exec($command .  ' 2>&1');
                        // dd($output);
                        // // check if extract is success
                        // if($output){
                        //     // // now delete the rar file
                        //     // unlink($project_dir."/".$product->file()[2]);
                        //     // now create a new file called project.json
                        //     $project = array(
                        //         "name" => $project,
                        //         "description" => "Extract from " . $request->base,
                        //         "status" => "active",
                        //         "created_at" => date("Y-m-d H:i:s"),
                        //         "updated_at" => date("Y-m-d H:i:s")
                        //     );
                        //     $project = json_encode($project);
                        //     file_put_contents($project_dir."/project.json", $project);
                        //     // return back with success
                        //     return back()->with('success', 'Project created successfully with RAR file');
                        // }else{
                        //     // revert back the project folder if error
                        //     rmdir($project_dir);
                        //     return redirect()->back()->with('error', 'Error extracting RAR file');
                        // }


                    }

            }else{
                // if the base is other than original, then go to projects subdirectory and check if the project exists with the base
                $base = $request->base;
                $base_dir = $product->shano->path . $product->shano->scrape_products."/".$id."/projects/".$base;
                if(file_exists($base_dir)){
                    // copy the contents inside  base directory into the project directory

                    $this->copyDir($base_dir, $project_dir);

                    // now create a new file called project.json
                    $project = array(
                        "name" => $project,
                        "description" => "Extract from " . $request->base,
                        "status" => "active",
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    $project = json_encode($project);
                    file_put_contents($project_dir."/project.json", $project);
                    // return back with success
                    return back()->with('success', 'Project created successfully');
                }else{
                    //  revert back
                    rmdir($project_dir);
                    return redirect()->back()->with('error', 'Error Copying project');
                }
            }

        } else {
            return redirect()->back()->with('error', 'Project Already Exists');
        }


        return view('scrape.create-project', compact('product'));
    }

    // copy directory
    public function copyDir($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyDir($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    // private function getImages(){
    //     // check if images folder exists
    //     $images = $this->shano->path . $this->shano->scrape_products."/".$this->product."/images";
    //     if(file_exists($images)){
    //         $images = scandir($images);
    //         foreach ($images as $key => $value) {
    //             if($value == "." || $value == ".."){
    //                 // remove this

    //             }else{
    //                 $images[$key] = $this->shano->server . $this->shano->scrape_products."/".$this->product."/images/".$value;

    //             }
    //             // add server to the image
    //         }
    //         return $images;
    //     }else{
    //         return false;
    //     }

    // }
}
