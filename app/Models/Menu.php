<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'url',
        'route_name',
        'icon',
        'menu_type',
        'parent_id',
        'sort_order',
        'is_active',
        'open_in_new_tab',
        'css_class',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent menu item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get all child menu items
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all active child menu items
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Scope to get only active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get menus by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('menu_type', $type);
    }

    /**
     * Scope to get only parent menus (no parent_id)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the full URL for the menu item
     */
    public function getFullUrlAttribute()
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                return $this->url ?: '#';
            }
        }

        return $this->url ?: '#';
    }

    /**
     * Check if menu has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Check if menu has active children
     */
    public function hasActiveChildren()
    {
        return $this->activeChildren()->count() > 0;
    }

    /**
     * Get menu depth level
     */
    public function getDepthLevel()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }
}
