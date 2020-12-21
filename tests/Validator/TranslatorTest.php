<?php


namespace Fwk\Tests\Validator;

use Fwk\Validator\Translator as FwkTranslator;
use Laminas\I18n\Translator\Translator;
use Mockery;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $translator = Mockery::mock(Translator::class);
        $message = 'message';
        $domain = 'domain';
        $locale = 'fr';

        $translator->shouldReceive('translate')
            ->once()
            ->with($message, $domain, $locale)
            ->andReturn('translated message');

        $validator = new FwkTranslator($translator);

        $this->assertEquals('translated message', $validator->translate($message, $domain, $locale));
    }
}
