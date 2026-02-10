<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Setting extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    protected $fillable = ['key', 'value'];
    protected $connection = "mysql";

    public static function get($key, $default = null)
    {
        return optional(static::where('key', $key)->first())->value ?? $default;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('site_logo')->singleFile();
        $this->addMediaCollection('site_logo_bottom')->singleFile();
    }

    /**
     * Get media resources for a specific collection
     * 
     * @param string $collection
     * @return array
     */
    public function getMediaResource($collection = 'default')
    {
        return $this->getMedia($collection)->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => $media->getUrl(),
                'thumb_url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(),
                'preview_url' => $media->hasGeneratedConversion('preview') ? $media->getUrl('preview') : $media->getUrl(),
                'collection_name' => $media->collection_name,
                'custom_properties' => $media->custom_properties,
                'responsive_images' => $media->responsive_images,
                'order_column' => $media->order_column,
            ];
        })->toArray();
    }
}
