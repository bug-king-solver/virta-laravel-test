<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function children()
    {
        return $this->hasMany(Company::class, 'parent_company_id');
    }
    public function getChildCompanies()
    {
        $childCompanies = $this->children;

        foreach ($this->children as $child) {
            $childCompanies = $childCompanies->merge($child->getChildCompanies());
        }

        return $childCompanies;
    }
}