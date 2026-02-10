<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'domain' => $this->domain,
            'domain_prefix' => $this->domain_prefix,
            'full_domain' => $this->domain_prefix ? "{$this->domain_prefix}.{$this->domain}" : $this->domain,
            'contact' => [
                'email' => $this->contact_email,
                'phone' => $this->contact_phone,
                'address_1' => $this->address_line1,
                'address_2' => $this->address_line2,
                'postal_code' => $this->postal_code,
            ],

            'status' => $this->status,
            'launched_at' => optional($this->launched_at)->toIso8601String(),
            'deactivated_at' => optional($this->deactivated_at)->toIso8601String(),
            'external_url' => $this->external_url,
            'website_theme' => $this->website_theme,
            'colors' => [
                'footer_background' => $this->footer_background_color,
                'header_background' => $this->header_background_color,
                'body_background' => $this->body_background_color,
                'menu_links' => $this->menu_links_color,
                'menu_hover' => $this->menu_hover_color,
                'fonts_scheme' => $this->fonts_color_scheme,
            ],
            'social_media' => [
                'facebook_url' => $this->facebook_url,
                'instagram_url' => $this->instagram_url,
                'twitter_url' => $this->twitter_url,
                'youtube_url' => $this->youtube_url,
            ],

            'front_page' => $this->front_page,
            'department' => $this->whenLoaded('department', function () {
                return [
                    'name' => $this->department->name,
                    'department_type' => $this->department->department_type,
                    'department_code' => $this->department->department_code,
                ];
            }),
            'logo' => $this->whenLoaded('logo', function () {
                return [
                    'uuid' => $this->logo->uuid ?? null,
                    'title' => $this->logo->title ?? null,
                    // adjust url logic to your Media model
                    'url' => asset('storage/' . $this->logo->file_path) ?? $this->logo->external_url ?? null,
                ];
            }),
            'enable_logo_animation' => $this->enable_logo_animation ?? null,
            'slider' => $this->whenLoaded('slider', function () {
                return [
                    'name' => $this->slider->name ?? null,
                    'slug' => $this->slider->slug ?? null,
                    'slides' => $this->slider->slides->map(function ($slide) {
                        return [
                            'id' => $slide->id,
                            'title' => $slide->title,
                            'image_url' => asset('storage/' . $slide->image_path), // Adjust image path logic
                            'link' => $slide->link,
                        ];
                    }),
                ];
            }),
            'meta' => $this->meta ?? (object) [],
        ];
    }
}
