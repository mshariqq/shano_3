<?php

// namespace a shano
namespace App;
class Shano{

    public $path = "E:\\CoProjects";
    public $server = "http://localhost:8005";

    public $clients = "/public/clients";
    public $scrape_products = "/private/products/scrape";

    public function __construct(){

    }

    public function getScrapeProducts(){
        $scrapeProducts = scandir("$this->path$this->scrape_products");
        return $scrapeProducts;
    }

    public function getScrapeProductImage($product){
        $image = $this->path. $this->scrape_products."/".$product."/thumbnail.png";
        // check if file exists then return
        if(file_exists($image)){
            return $this->server. $this->scrape_products."/".$product."/thumbnail.png";
        }else{
            $image1 = $this->path. $this->scrape_products."/".$product."/thumbnail.jpeg";
            if(file_exists($image1)){
                return $this->server. $this->scrape_products."/".$product."/thumbnail.jpeg";
            }else{
                $image2 = $this->path. $this->scrape_products."/".$product."/thumbnail.jpg";
                if(file_exists($image2)){
                    return  $this->server. $this->scrape_products."/".$product."/thumbnail.jpg";
                }else{
                     // image3 for JPG
                     $image3 = $this->path. $this->scrape_products."/".$product."/thumbnail.JPG";
                     if(file_exists($image3)){
                         return $this->server. $this->scrape_products."/".$product."/thumbnail.JPG";
                     }else{
                         return $this->server. $this->scrape_products."/".$product."/thumbnail.jpg";
                     }
                }
            }
        }

    }

    public function getScrapeProduct($product){
        $product = $this->path. $this->scrape_products."/".$product;
        $product = scandir($product);
        return $product;
    }

    public function newScrapeProduct($name){
        $new_dir = $this->path . $this->scrape_products."/".$name;
        if(!file_exists($new_dir)){
           if(mkdir($new_dir)){
            // create new dir called images inside this new one
            mkdir($new_dir."/images");
            // create new dir files inside this new one
            mkdir($new_dir."/files");
            // create new dir called data inside this new one
            mkdir($new_dir."/data");

            return true;
           }else{
            return false;
           }
        }
    }

    // create new directory inside the given path
    public function new_client($name){
        $new_dir = $this->path . $this->clients.$name;
        if(!file_exists($new_dir)){
            mkdir($new_dir, 0755);
        }
        return $new_dir;
    }

    public function browse($dir){
        $files = scandir($dir);
        return $files;
    }

    public function exe($command){
        $output = shell_exec($command . " 2>&1");
        return $output;
    }
}
