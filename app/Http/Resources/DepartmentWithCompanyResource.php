<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\CommonMethods;

class DepartmentWithCompanyResource extends JsonResource
{
    use CommonMethods;
    public function toArray(Request $request): array
    {
        $company = $this->whenLoaded('company');
        $media = $this->whenLoaded('media');
        $cover = $this->whenLoaded('coverMedia');
        $companyMediaUrl =  ($company && !empty($company->media->file_path)) ? $this->getMediaUrl($company->media)  : null;
        $mediaUrl = ($media && !empty($media->file_path)) ? $this->getMediaUrl($media) : null;

        $coverUrl = ($cover && !empty($cover->file_path)) ? $this->getMediaUrl($cover) : null;

        return [
            'id' => $this->id,
            'uuid'            => $this->uuid,
            'name'            => $this->name,
            'department_code' => $this->department_code,
            'department_type' =>  $this->department_type,
            'status'          => $this->status,
            'has_website'     => $this->has_website,
            'external_url'    => $this->external_url,
            'department_logo' => $mediaUrl,
            'department_cover_image' => $coverUrl,
            'site_logo' => $companyMediaUrl,
            // // department media
            // 'media' => $media ? [
            //     'uuid'      => $media->uuid,
            //     'title'     => $media->title ?? null,
            //     'extension' => $media->extension ?? null,
            //     'kind'      => $media->kind ?? null,
            //     'url'       => $mediaUrl,
            // ] : null,

            // 'cover_media' => $cover ? [
            //     'uuid'      => $cover->uuid,
            //     'title'     => $cover->title ?? null,
            //     'extension' => $cover->extension ?? null,
            //     'kind'      => $cover->kind ?? null,
            //     'url'       => $coverUrl,
            // ] : null,

            // company will be null if no matching row exists
            'site' => $company ? [
                'uuid'          => $company->uuid,
                'title'         => $company->title,
                'domain'        => $company->domain,
                'domain_prefix' => $company->domain_prefix,
                'status'        => $company->status,
                'external_url'  => $company->external_url,
                // optional if you load company.logo:
                // 'logo' => ($company->relationLoaded('logo') && $company->logo && $company->logo->file_path)
                //     ? asset('storage/' . $company->logo->file_path)
                //     : null,
            ] : null,
        ];
    }
}
