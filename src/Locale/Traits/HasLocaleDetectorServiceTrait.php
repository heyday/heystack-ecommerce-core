<?php

namespace Heystack\Ecommerce\Locale\Traits;

use Heystack\Ecommerce\Locale\Interfaces\LocaleDetectionInterface;

/**
 * @package Heystack\Ecommerce\Locale\Traits
 */
trait HasLocaleDetectorServiceTrait
{
    /**
     * @var \Heystack\Ecommerce\Locale\Interfaces\LocaleDetectionInterface
     */
    protected $localeDetector;

    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\LocaleDetectionInterface $localeDetector
     */
    public function setLocaleDetector(LocaleDetectionInterface $localeDetector)
    {
        $this->localeDetector = $localeDetector;
    }

    /**
     * @return mixed
     */
    public function getLocaleDetector()
    {
        return $this->localeDetector;
    }
}