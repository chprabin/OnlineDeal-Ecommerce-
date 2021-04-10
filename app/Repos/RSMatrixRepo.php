<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\RSMatrix;
use Illuminate\Support\Facades\DB;

class RSMatrixRepo extends Repository{
    public function __construct(RSMatrix $model){
        parent::__construct($model);
    }

    public function updateMany(array $updatable_items)
    {   
       $sql="update rs_matrix ";
       $updatable_columns=['sum','count'];
       foreach($updatable_columns as $index=>$cname){
        $sql.=$index==0?"set ":", ";
        $sql.=$cname."=(case ";
        foreach($updatable_items as $row){
            $sql.="when (itemId1='".$row['itemId1']."' and itemId2='".$row['itemId2']."') then '".
            $row[$cname]."'";
        }
        $sql.=" else ".$cname." end)";
       }
       DB::update($sql);
    }

    public function insertMany(array $insertable_items){
        $first_row=$insertable_items[0];
        $sql="insert into rs_matrix (".implode(',',array_keys($first_row)).") values ('";
        foreach($insertable_items as $key=>$row){
            $sql.=implode("','",$row)."')";
            if($key<count($insertable_items)-1){
                $sql.=", ('";
            }
        }
        DB::insert($sql);
    }
}