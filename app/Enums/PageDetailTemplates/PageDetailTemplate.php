<?php

namespace App\Enums\PageDetailTemplates;

enum PageDetailTemplate: string
{
    // Jobs Templates
    case JOBS_DEFAULT = 'jobs-default';

    // Services Templates
    case SERVICES_DEFAULT = 'services-default';

    // Tenders Templates
    case TENDERS_DEFAULT = 'tenders-default';

    // Rules and Regulations Templates
    case RULES_AND_REGULATIONS_DEFAULT = 'rules-and-regulations-default';
    case DOWNLOADS_DEFAULT = 'downloads-default';
    case FAQS_DEFAULT = 'faqs-default';
    case FOOTER_RELATED_LINKS = 'footer-related-links';
    case FOOTER_TERMS_AND_CONDITIONS = 'footer-terms-and-conditions';
    case FOOTER_PRIVACY_POLICY = 'footer-privacy-policy';

    // Our Heroes
    case OUR_HEROES_DEFAULT = 'our_heroes-default';


    /**
     * Get which module type this template belongs to
     */
    public function moduleType(): string
    {
        if (str_starts_with($this->value, 'jobs-')) {
            return 'jobs';
        }
        if (str_starts_with($this->value, 'services-')) {
            return 'services';
        }
        if (str_starts_with($this->value, 'tenders-')) {
            return 'tenders';
        }
        if (str_starts_with($this->value, 'rules-and-regulations-')) {
            return 'rules_and_regulations';
        }
        if (str_starts_with($this->value, 'downloads-')) {
            return 'downloads';
        }
        if (str_starts_with($this->value, 'faqs-')) {
            return 'faqs';
        }
        if (str_starts_with($this->value, 'footer-')) {
            return 'footer';
        }
        if (str_starts_with($this->value, 'our_heroes-')) {
            return 'our_heroes';
        }
        return 'unknown';
    }

    /**
     * Get human-readable label for this template
     */
    public function label(): string
    {
        $labels = [
            // Jobs
            'jobs-default' => 'Jobs Default Theme',

            // Services
            'services-default' => 'Services Default Theme',

            // Tenders
            'tenders-default' => 'Tenders Default Theme',

            // Rules and Regulations
            'rules-and-regulations-default' => 'Rules and Regulations Default Theme',

            'downloads-default' => 'Downloads Default Theme',
            'faqs-default' => 'Faqs Default Theme',
            'footer-related-links' => 'Related Links',
            'footer-terms-and-conditions' => 'Terms and Conditions',
            'footer-privacy-policy' => 'Privacy Policy',

            // Our Heroes
            'our_heroes-default' => 'Our Heroes Default Theme',

        ];

        return $labels[$this->value] ?? 'Unknown Template';
    }

    /**
     * Get React component name for this template
     */
    public function component(): string
    {
        $components = [
            // Jobs
            'jobs-default' => 'JobsGrid',

            // Services
            'services-default' => 'ServicesGrid',

            // Tenders
            'tenders-default' => 'TendersGrid',

            // Rules and Regulations
            'rules-and-regulations-default' => 'RulesAndRegulationsGrid',

            'downloads-default' => 'DownloadsGrid',

            'faqs-default' => 'FaqsDefault',
            'footer-related-links' => 'RelatedLinks',
            'footer-terms-and-conditions' => 'TermsAndConditions',
            'footer-privacy-policy' => 'PrivacyPolicy',

            // Our Heroes
            'our_heroes-default' => 'OurHeroesGrid',

        ];

        return $components[$this->value] ?? 'DefaultView';
    }

    /**
     * Get configuration array for this template
     */
    public function config(): array
    {
        $configs = [
            // Jobs

            'jobs-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],
            // Services

            'services-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],
            // Tenders

            'tenders-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],

            // Rules and Regulations

            'rules-and-regulations-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],
            // Downloads

            'downloads-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],

            'faqs-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],
            'footer-related-links' => [
                'pagination' => false,
            ],
            'footer-terms-and-conditions' => [
                'pagination' => false,
            ],
            'footer-privacy-policy' => [
                'pagination' => false,
            ],
            // Our Heroes
            'our_heroes-default' => [
                'items_per_row' => 3,
                'show_filters' => true,
                'pagination' => true,
                'per_page' => 12,
            ],
        ];

        return $configs[$this->value] ?? [
            'items_per_row' => 3,
            'show_filters' => true,
            'pagination' => true,
            'per_page' => 12,
        ];
    }

    /**
     * Get all templates for a specific module type
     */
    public static function forModuleType(string $moduleType): array
    {
        $allTemplates = self::cases();
        $filtered = [];

        foreach ($allTemplates as $template) {
            if ($template->moduleType() === $moduleType) {
                $filtered[] = $template;
            }
        }

        return $filtered;
    }

    /**
     * Get options array for dropdown/select (used in forms)
     */
    public static function optionsForModule(string $moduleType): array
    {
        $templates = self::forModuleType($moduleType);
        $options = [];

        foreach ($templates as $template) {
            $options[] = [
                'value' => $template->value,
                'label' => $template->label(),
                'component' => $template->component(),
                'config' => $template->config(),
            ];
        }

        return $options;
    }

    /**
     * Try to get template from module type and template value
     */
    public static function tryFromModule(string $moduleType, ?string $templateValue): ?self
    {
        if (!$templateValue) {
            return null;
        }

        $template = self::tryFrom($templateValue);
        if ($template && $template->moduleType() === $moduleType) {
            return $template;
        }

        return null;
    }
}
