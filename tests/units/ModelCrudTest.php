<?php namespace FelipeeDev\Utilities;

use FelipeeDev\Utilities\Eloquent\ModelCrud;
use FelipeeDev\Utilities\Eloquent\ModelRepository;
use FelipeeDev\Utilities\Testing\PackageTestCase;
use FelipeeDev\Utilities\Validation\Rules;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;

class ModelCrudTest extends PackageTestCase
{
    public function test_create()
    {
        $model = m::mock(new TestModel);
        $model->shouldReceive('save');

        $repository = m::mock(TestModelRepository::class);
        $repository->shouldReceive('newInstance')->andReturn($model);

        $service = new ModelCrudServiceExample;

        $service->setDependency('repository', $repository);

        $resultModel = $service->create(['foo' => 'bar']);

        $this->assertSame($model, $resultModel);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function test_create_fails()
    {
        $service = new ModelCrudServiceExample;

        $service->setDependency('repository', new TestModelRepository(new TestModel));

        $service->setDependency('rules', new TestRules);

        $service->create(['foo' => '']);
    }

    public function test_update()
    {
        $model = m::mock(new TestModel(['foo' => 'bar']));
        $model->shouldReceive('save');

        $service = new ModelCrudServiceExample;

        $service->update($model, ['foo' => 'bar2']);

        $this->assertSame('bar2', $model->foo);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function test_update_fails()
    {
        $model = new TestModel(['foo' => 'bar']);

        $service = new ModelCrudServiceExample;

        $service->setDependency('rules', new TestRules);

        $service->update($model, ['foo' => '']);
    }

    public function test_store_or_update()
    {
        $model = m::mock(new TestModel);
        $model->shouldReceive('save');

        $service = new ModelCrudServiceExample;

        $service->storeOrUpdate($model, ['foo' => 'bar']);

        $this->assertSame('bar', $model->foo);

        $model = m::mock(new TestModel(['foo' => '']));
        $model->id = 123;
        $model->shouldReceive('save');

        $service->storeOrUpdate($model, ['foo' => 'bar-2']);

        $this->assertSame('bar-2', $model->foo);
        $this->assertSame(123, $model->id);
    }
}

class TestModel extends Model
{
    protected $fillable = ['foo'];
}

class TestModelRepository extends ModelRepository
{
    protected $model;

    public function __construct(TestModel $model)
    {
        $this->model = $model;
    }
}

class TestRules implements Rules
{
    public function getRules(string $type = null): array
    {
        return ['foo' => 'required'];
    }
}

class ModelCrudServiceExample
{
    use ModelCrud;

    protected $dependencies = [

    ];
}
