<?php

namespace Tests\Feature\Comments;

use App\Http\Livewire\IdeaComment;
use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\IdeaShow;
use App\Http\Livewire\MarkCommentAsNotSpam;
use App\Http\Livewire\MarkCommentAsSpam;
use App\Http\Livewire\MarkIdeaAsNotSpam;
use App\Http\Livewire\MarkIdeaAsSpam;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_comment_as_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function do_not_show_mark_comment_as_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $this
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('marl-idea-as-spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $response = Livewire::actingAs($user)
            ->test(MarkCommentAsSpam::class)
            ->call('setMarkAsSpamComment', $comment->id)
            ->assertEmitted('markAsSpamCommentWasSet');

        $response->call('markAsSpam');

        $this->assertEquals(1, Comment::first()->spam_reports);
    }

    /** @test */
    public function marking_a_comment_as_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(MarkCommentAsSpam::class)
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        Livewire::test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $user->id,
            ])
            ->assertDontSee('Mark as Spam');
    }

    /** @test */
    public function shows_mark_comment_as_not_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();
        Comment::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function do_not_show_mark_comment_as_not_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function resetting_spam_comment_works_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $comment = Comment::factory()->create();

        $response = Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkAsNotSpamComment', $comment->id)
            ->assertEmitted('markAsNotSpamCommentWasSet');

        $response->call('markAsNotSpam');

        $this->assertEquals(0, Comment::first()->spam_reports);
    }

    /** @test */
    public function marking_an_comment_as_not_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'spam_reports' => 2,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $user->id,
            ])
            ->assertSee('Not Spam');
    }

    /** @test */
    public function marking_an_comment_as_not_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    { 
        $user = User::factory()->create();
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'spam_reports' => 2,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $user->id,
            ])
            ->assertDontSee('Not Spam');
    }

    /** @test */
    public function spam_reports_count_shows_on_idea_comment_component_if_logged_in_as_admin()
    { 
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'spam_reports' => 2,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $user->id,
            ])
            ->assertSee('Spam Reports: 2');
    }
}
