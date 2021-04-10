<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\ProductImage;

class ProductImageRepo extends Repository{
    protected $upload_path='images/product_images';
    public function __construct(ProductImage $model){
        parent::__construct($model);
    }

    public function uploadImage($image){
        return $image->store($this->upload_path);
    }
}