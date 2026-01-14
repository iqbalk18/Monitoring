<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareProv extends Model
{
    protected $table = 'careprovider';

    protected $fillable = [
        'CTPCP_Code',
        'CTPCP_Desc',
        'CTCPT_Code',
        'CTCPT_Desc',
        'CTPCP_Stname',
        'CTPCP_Level',
        'CTSPC_Spec_Code',
        'CTSPC_Spec_Desc',
        'CTSPC_SubSpec_Code',
        'CTSPC_SubSpec_Desc',
        'CTPCP_TelH',
        'CTPCP_PagerNo',
        'CTPCP_SpecialistYN',
        'CTPCP_DateActiveFrom',
        'CTPCP_DateActiveTo',
        'CTPCP_PrescriberNumber',
        'RU_Code',
        'RU_Desc',
        'CTLOC_Code',
        'CTLOC_Desc',
        'CTPCP_Title',
        'CTPCP_DOB',
        'CTPCP_MobilePhone',
        'CTPCP_Fax',
        'CTPCP_Email',
        'CTPCP_FirstName',
        'TTL_Code',
        'TTL_Desc',
        'CTPCP_Surname',
        'CTPCP_ConfidentialFax',
        'CTPCP_CodeTranslated',
        'CTPCP_DescTranslated',
    ];

    protected $casts = [
        'CTPCP_DateActiveFrom' => 'date',
    ];
}