<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_of_ideas_shows_on_main_page()
    {
        $category_one = Category::factory()->create(['name' => 'Category 1']);
        $category_two = Category::factory()->create(['name' => 'Category 2']);

        $statusOpenUnique = Status::factory()->create(['name' => 'OpenUnique']);
        $statusConsideringUnique = Status::factory()->create(['name' => 'ConsideringUnique']);

        $idea_one = Idea::factory()->create([
            'category_id' => $category_one->id,
            'status_id' => $statusOpenUnique,
        ]);

        $idea_two = Idea::factory()->create([
            'category_id' => $category_two->id,
            'status_id' => $statusConsideringUnique,
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee($idea_one->title);
        $response->assertSee($idea_one->description);
        $response->assertSee($category_one->name);
        $response->assertSee('OpenUnique');
        $response->assertSee($idea_two->title);
        $response->assertSee($idea_two->description);
        $response->assertSee($category_two->name);
        $response->assertSee('ConsideringUnique');
    }

    /** @test */
    public function single_idea_shows_correctly_on_the_show_page()
    {
        $category = Category::factory()->create(['name' => 'Category 1']);

        $status = Status::factory()->create(['name' => 'StatusUnique']);

        $idea = Idea::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        $response = $this->get(route('idea.show', $idea));

        $response->assertSuccessful();
        $response->assertSee($idea->title);
        $response->assertSee(nl2br($idea->description), false);
        $response->assertSee($category->name);
        $response->assertSee('StatusUnique');
    }

    /** @test */
    public function ideas_pagination_works()
    {
        $idea_one = Idea::factory()->create();

        Idea::factory($idea_one->getPerPage())->create();

        $response = $this->get('/');

        $response->assertDontSee(Idea::find(Idea::count())->title);
        $response->assertSee($idea_one->title);

        $response = $this->get('/?page=2');

        $response->assertSee(Idea::find(Idea::count())->title);
        $response->assertDontSee($idea_one->title);
    }

    /** @test */
    public function same_idea_title_different_slugs()
    {
        $idea_one = Idea::factory()->create([
            'title' => 'My First Idea',
        ]);

        $idea_two = Idea::factory()->create([
            'title' => 'My First Idea',
        ]);

        $response = $this->get(route('idea.show', $idea_one));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea');

        $response = $this->get(route('idea.show', $idea_two));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea-2');
    }

    /** @test */
    public function in_app_back_button_works_when_index_page_visited_first()
    {
        $ideaOne = Idea::factory()->create();

        $response = $this->get('/?category=Category%202&status=Considering');
        $response = $this->get(route('idea.show', $ideaOne));

        $this->assertStringContainsString('/?category=Category%202&status=Considering', $response['backUrl']);
    }

    /** @test */
    public function in_app_back_button_works_when_show_page_only_page_visited()
    {
        $ideaOne = Idea::factory()->create();

        $response = $this->get(route('idea.show', $ideaOne));

        $this->assertEquals(route('idea.index'), $response['backUrl']);
    }
}
