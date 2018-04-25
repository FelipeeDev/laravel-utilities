<?php namespace FelipeeDev\Utilities;

use FelipeeDev\Utilities\Eloquent\ModelAccess;
use FelipeeDev\Utilities\Eloquent\ModelSetterAndGetter;
use FelipeeDev\Utilities\Testing\PackageTestCase;
use FelipeeDev\Utilities\Validation\Rules;
use Illuminate\Database\Eloquent\Model;

class RulesValidatorTest extends PackageTestCase
{
    public function testValidateRules()
    {
        $validator = $this->getRuleValidator();

        $rules = new RulesExample;

        $validator->validate(['foo' => 'bar'], $rules);
        
        $this->assertTrue(true);
    }

    public function testValidateModelRules()
    {
        $validator = $this->getRuleValidator();

        $model = new ModelExample(['foo' => 'bar']);

        $rules = new RulesExample;

        $validator->validateModel($model, $rules);

        $this->assertSame($model, $rules->getModel());
    }

    /**
     * @expectedException \Error
     */
    public function testValidateRulesBadType()
    {
        $validator = $this->getRuleValidator();

        $rules = 'foo';

        $validator->validate(['foo' => 'bar'], $rules);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function testValidateFails()
    {
        $validator = $this->getRuleValidator();

        $rules = new RulesExample();

        $validator->validate(['bar' => 'xxx'], $rules);
    }

    protected function getRuleValidator()
    {
        return new RulesValidator;
    }
}

class RulesExample implements Rules, ModelAccess
{
    use ModelSetterAndGetter;

    public function getRules(string $type = null): array
    {
        return ['foo' => 'required'];
    }
}

class ModelExample extends Model
{
    protected $fillable = ['foo'];
}
