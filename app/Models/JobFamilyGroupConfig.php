<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFamilyGroupConfig extends Model
{
    protected $table = 'job_family_group_config';

    protected $fillable = ['job_family', 'job_family_group'];
    
     /**
     * Define a relationship with the GenericData model for the job family.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobFamily()
    {
        return $this->belongsTo(GenericData::class, 'job_family', 'id');
    }

    /**
     * Define a relationship with the GenericData model for the job family group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobFamilyGroup()
    {
        return $this->belongsTo(GenericData::class, 'job_family_group', 'id');
    }
}
