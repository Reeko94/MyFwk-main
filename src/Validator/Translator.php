<?php


namespace Fwk\Validator;

use Laminas\Validator\Translator\TranslatorInterface as ValidatorTranslatorInterface;
use Laminas\I18n\Translator\TranslatorInterface as I18nTranslatorInterface;

class Translator implements ValidatorTranslatorInterface
{
    /**
     * @var I18nTranslatorInterface
     */
    protected I18nTranslatorInterface $translator;

    /**
     * Translator constructor.
     * @param I18nTranslatorInterface $translator
     */
    public function __construct(I18nTranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     * @param string $textDomain
     * @param null $locale
     * @return string
     */
    public function translate($message, $textDomain = 'default', $locale = null): string
    {
        return $this->translator->translate($message, $textDomain, $locale);
    }
}
