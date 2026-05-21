<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CategorySortAndCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_categories_are_sorted_by_position(): void
    {
        // Create root categories in arbitrary order with positions
        Category::forceCreate(['name' => 'Cat B', 'slug' => 'cat-b', 'parent_id' => null, 'position' => 2, 'status' => true]);
        Category::forceCreate(['name' => 'Cat C', 'slug' => 'cat-c', 'parent_id' => null, 'position' => 3, 'status' => true]);
        Category::forceCreate(['name' => 'Cat A', 'slug' => 'cat-a', 'parent_id' => null, 'position' => 1, 'status' => true]);

        $categories = Category::where('status', true)->whereNull('parent_id')->orderBy('position', 'asc')->get();

        $this->assertEquals('Cat A', $categories[0]->name);
        $this->assertEquals('Cat B', $categories[1]->name);
        $this->assertEquals('Cat C', $categories[2]->name);
    }

    public function test_children_categories_relationship_is_sorted_by_position(): void
    {
        $parent = Category::forceCreate(['name' => 'Parent', 'slug' => 'parent', 'parent_id' => null, 'position' => 1, 'status' => true]);
        
        Category::forceCreate(['name' => 'Child 2', 'slug' => 'child-2', 'parent_id' => $parent->id, 'position' => 2, 'status' => true]);
        Category::forceCreate(['name' => 'Child 3', 'slug' => 'child-3', 'parent_id' => $parent->id, 'position' => 3, 'status' => true]);
        Category::forceCreate(['name' => 'Child 1', 'slug' => 'child-1', 'parent_id' => $parent->id, 'position' => 1, 'status' => true]);

        $children = $parent->fresh()->children;

        $this->assertCount(3, $children);
        $this->assertEquals('Child 1', $children[0]->name);
        $this->assertEquals('Child 2', $children[1]->name);
        $this->assertEquals('Child 3', $children[2]->name);
    }

    public function test_category_events_invalidate_global_view_data_cache(): void
    {
        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $category = Category::forceCreate(['name' => 'New Cat', 'slug' => 'new-cat', 'status' => true]);
        $this->assertFalse(Cache::has('global_view_data'));

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $category->update(['name' => 'Updated Cat']);
        $this->assertFalse(Cache::has('global_view_data'));

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $category->delete();
        $this->assertFalse(Cache::has('global_view_data'));
    }

    public function test_page_events_invalidate_global_view_data_cache(): void
    {
        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $page = Page::create([
            'title' => 'Test Page',
            'content' => 'Page content',
            'is_visible_in_footer' => true,
            'is_active' => true
        ]);
        $this->assertFalse(Cache::has('global_view_data'));

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $page->update(['title' => 'Updated Page']);
        $this->assertFalse(Cache::has('global_view_data'));

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $page->delete();
        $this->assertFalse(Cache::has('global_view_data'));
    }

    public function test_setting_events_invalidate_global_view_data_cache(): void
    {
        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $setting = Setting::create(['key' => 'test_key', 'value' => 'value', 'type' => 'string']);
        $this->assertFalse(Cache::has('global_view_data'));

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $setting->update(['value' => 'new_value']);
        $this->assertFalse(Cache::has('global_view_data'));

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        $setting->delete();
        $this->assertFalse(Cache::has('global_view_data'));
    }

    public function test_filament_reorder_table_invalidates_cache(): void
    {
        $cat1 = Category::forceCreate(['name' => 'Cat 1', 'slug' => 'cat-1', 'position' => 1]);
        $cat2 = Category::forceCreate(['name' => 'Cat 2', 'slug' => 'cat-2', 'position' => 2]);

        Cache::put('global_view_data', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('global_view_data'));

        // Instantiate ManageCategories Page component and call reorderTable
        $pageComponent = new \App\Filament\Resources\CategoryResource\Pages\ManageCategories();
        $pageComponent->reorderTable([$cat2->id, $cat1->id]);

        // Assert categories positions are updated in DB
        $this->assertEquals(1, $cat2->fresh()->position);
        $this->assertEquals(2, $cat1->fresh()->position);

        // Assert cache is cleared
        $this->assertFalse(Cache::has('global_view_data'));
    }
}
