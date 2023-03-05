<?php

namespace Tests\Feature;

use App\Http\Livewire\CreateIdea;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_idea_form_does_not_show_when_logged_out()
    {
        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee('Please login to create an idea.');
        $response->assertDontSee('Let us know what you would like and we\'ll take a look over!');
    }

    /** @test */
    public function create_idea_form_does_show_when_logged_in()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertDontSee('Please login to create an idea.');
        $response->assertSee('Let us know what you would like and we\'ll take a look over!', false);
    }

    /** @test */
    public function main_page_contains_create_idea_livewire_component()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('idea.index'))
            ->assertSeeLivewire('create-idea');
    }

    /** @test */
    public function create_idea_form_validation_works()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateIdea::class)
            ->set('title', '')
            ->set('category', '')
            ->set('description', '')
            ->call('create_idea')
            ->assertHasErrors(['title', 'category', 'description'])
            ->assertSee('The title field is required');
    }

    /** @test */
    public function creating_an_idea_works_correctly()
    {
        $user = User::factory()->create();

        $category_one = Category::factory()->create(['name' => 'Category 1']);

        Status::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title', 'My First Idea')
            ->set('category', $category_one->id)
            ->set('description', 'Description of my first idea')
            ->call('create_idea')
            ->assertRedirect('/');

        $response = $this->actingAs($user)->get(route('idea.index'));
        $response->assertSuccessful();
        $response->assertSee('My First Idea');
        $response->assertSee('Description of my first idea');
        
        $this->assertDatabaseHas('ideas', [
            'title' => 'My First Idea',
        ]);

        $this->assertDatabaseHas('votes', [
            'idea_id' => 1,
            'user_id' => 1,
        ]);
    }

    /** @test */
    public function creating_two_ideas_with_same_title_still_works_but_has_different_slugs()
    {
        $user = User::factory()->create();

        $category_one = Category::factory()->create(['name' => 'Category 1']);
        Status::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title', 'My First Idea')
            ->set('category', $category_one->id)
            ->set('description', 'Description of my first idea')
            ->call('create_idea')
            ->assertRedirect('/');
        
        $this->assertDatabaseHas('ideas', [
            'title' => 'My First Idea',
            'slug' => 'my-first-idea',
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title', 'My First Idea')
            ->set('category', $category_one->id)
            ->set('description', 'Description of my first idea')
            ->call('create_idea')
            ->assertRedirect('/');
        
        $this->assertDatabaseHas('ideas', [
            'title' => 'My First Idea',
            'slug' => 'my-first-idea-2',
        ]);
    }
}
