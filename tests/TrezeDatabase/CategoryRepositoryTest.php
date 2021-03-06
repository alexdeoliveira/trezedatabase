<?php

namespace TrezeVel\TrezeDatabase\Tests;

use TrezeVel\TrezeDatabase\Models\Category;
use TrezeVel\TrezeDatabase\Repository\CategoryRepository;
use Mockery as m;

/**
* Model de teste da categoria
*/
class CategoryRepositoryTest extends AbstractTestCase
{

    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->migrate();
        $this->repository = new CategoryRepository();
        $this->createCategory();
    }

    public function testCanModel()
    {
        $this->assertEquals(Category::class, $this->repository->model());
    }

    public function testCatMakeModel()
    {

        $result = $this->repository->makeModel();
        $this->assertInstanceOf(Category::class, $result);

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }


    public function testCanMakeModelInConstructor()
    {

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function testCanListAllCategories()
    {
        $result = $this->repository->all();
        $this->assertCount(3, $result);

        $this->assertNotNull($result[0]->description);

        $result = $this->repository->all(['name']);
        $this->assertNull($result[0]->description);
    }

    public function testCanCreateCategory()
    {
        $result = $this->repository->create([
            'name' => 'Name 4',
            'description' => 'Description 4'
        ]);
        $this->assertInstanceOf(Category::class, $result);

        $this->assertEquals('Name 4', $result->name);
        $this->assertEquals('Description 4', $result->description);

        $result = Category::find(4);
        $this->assertEquals('Name 4', $result->name);
        $this->assertEquals('Description 4', $result->description);

    }

    public function testCanUpdateCategory()
    {
        $result = $this->repository->update([
            'name' => 'Name atualizado',
            'description' => 'Description atualizado'
        ], 1);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Name atualizado', $result->name);
        $this->assertEquals('Description atualizado', $result->description);

        $result = Category::find(1);
        $this->assertEquals('Name atualizado', $result->name);
        $this->assertEquals('Description atualizado', $result->description);

    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testCanUpdateCategoryFail()
    {
        $this->repository->update([
            'name' => 'Name atualizado',
            'description' => 'Description atualizado'
        ], 10);

    }

    public function testCanDeleteCategory()
    {
        $result = $this->repository->delete(1);
        
        $categories = Category::all();
        $this->assertCount(2, $categories);

        $this->assertEquals(true, $result);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testCanDeleteCategoryFail()
    {
        $this->repository->delete(10);

    }

    public function testCanFindCategory()
    {
        $result = $this->repository->find(1);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function testCanFindCategoryWithColumns()
    {
        $result = $this->repository->find(1, ['name']);
        $this->assertInstanceOf(Category::class, $result);
        $this->assertNull($result->description);
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testCanFindCategoryFail()
    {
        $this->repository->find(10);

    }

    public function testCanFindCategories()
    {
        $result = $this->repository->findBy('name', 'Name 1');
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Category::class, $result[0]);
        $this->assertEquals('Name 1', $result[0]->name);

        $result = $this->repository->findBy('name', 'Name 10');
        $this->assertCount(0, $result);

        $result = $this->repository->findBy('name', 'Name 1', ['name']);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Category::class, $result[0]);
        $this->assertNull($result[0]->description);
    }

    public function createCategory()
    {
        Category::create([
            'name' => 'Name 1',
            'description' => 'Description 1',
        ]);

        Category::create([
            'name' => 'Name 2',
            'description' => 'Description 2',
        ]);

        Category::create([
            'name' => 'Name 3',
            'description' => 'Description 3',
        ]);
    }
}
