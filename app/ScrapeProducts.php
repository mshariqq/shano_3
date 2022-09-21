<?php

namespace App;

class ScrapeProducts
{
    public $product;
    public $shano;
    public $data;

    // construct
    public function __construct($product){
        $this->product = $product;
        $this->shano = new \App\Shano();
        $this->data = file_get_contents($this->shano->path . $this->shano->scrape_products."/".$this->product."/product.json");
        $this->data = json_decode($this->data);
    }

    public function thumbnail(){
        return $this->shano->server . $this->shano->scrape_products . "/" . $this->product . "/thumbnail.png";
    }

    public function tagline(){
        return $this->data->tagline;
    }


    // descriptin from data
    public function description(){
        return $this->data->description;
    }

    // link from data
    public function link(){
        return $this->data->link;
    }

    // name from data
    public function name(){
        return $this->data->name;
    }

    // created_at from data
    public function created_at(){
        return $this->data->created_at;
    }

    // status from data
    public function status(){
        return $this->data->status;
    }

    // get contents from subdirectory file of this product
    public function file(){
        return scandir($this->shano->path . $this->shano->scrape_products."/".$this->product."/file/");
    }

    // get images from subdirectory images
    public function images(){
        $images = scandir($this->shano->path . $this->shano->scrape_products."/".$this->product."/images/");
        // foreach them, add server in place of path
        foreach($images as $key => $image){
            if($image == "." || $image == ".."){
                unset($images[$key]);
            }else{
                $images[$key] = $this->shano->server . $this->shano->scrape_products . "/" . $this->product . "/images/" . $image;
            }
        }
        return $images;
    }

    // get projects from subdirectory projects
    public function projects(){
        return scandir($this->shano->path . $this->shano->scrape_products."/".$this->product."/projects/");
    }

    // get file name from subdirectory file
    public function fileName(){
        $file = scandir($this->shano->path . $this->shano->scrape_products."/".$this->product."/file/");
        // foreach($file as $key => $f){
        //     if($f == "." || $f == ".."){
        //         unset($file[$key]);
        //     }else{
        //         $file[$key] = $f;
        //     }
        // }
        return $file[2];
    }

    // get project path from subdirectory projects
    public function projectPath($item){
        $project = $this->shano->path . $this->shano->scrape_products."/".$this->product."/projects/".$item;
        // foreach($project as $key => $p){
        //     if($p == "." || $p == ".."){
        //         unset($project[$key]);
        //     }else{
        //         $project[$key] = $p;
        //     }
        // }
        return $project;
    }


}
