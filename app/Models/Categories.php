<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    
    protected $table = 'categories';
    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'Name',
        'Is_active',
        'Parent_id',
        'Image',
        'created_at',
        'updated_at'
    ];

    // Relationship với danh mục cha
    public function parent()
    {
        return $this->belongsTo(Categories::class, 'Parent_id', 'ID');
    }

    // Relationship với danh mục con
    public function children()
    {
        return $this->hasMany(Categories::class, 'Parent_id', 'ID');
    }

    public function loadAll()
    {
        return $this->with('parent')->orderBy('ID', 'desc')->paginate(4);
    }

    public function loadOneID($id)
    {
        return $this->with('parent')->find($id);
    }

    public function insertData($params)
    {
        return $this->create($params);
    }

    public function updateData($params, $id)
    {
        return $this->find($id)->update($params);
    }

    public function deleteData($id)
    {
        return $this->find($id)->delete();
    }
}
