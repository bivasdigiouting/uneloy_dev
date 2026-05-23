<?php

namespace Database\Seeders;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Primary Menu Items
        $primaryMenus = [
            [
                'title' => 'Home',
                'menu_type' => 'primary',
                'parent_id' => null,
                'url' => '/',
                'route_name' => null,
                'description' => 'Homepage',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 1,
                'icon' => 'fa fa-home',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'About Us',
                'menu_type' => 'primary',
                'parent_id' => null,
                'url' => '#',
                'route_name' => null,
                'description' => 'About our company',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 2,
                'icon' => 'fa fa-info-circle',
                'css_class' => 'has-nav',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Services',
                'menu_type' => 'primary',
                'parent_id' => null,
                'url' => '#',
                'route_name' => null,
                'description' => 'Our services',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 3,
                'icon' => 'fa fa-cogs',
                'css_class' => 'has-nav',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Contact Us',
                'menu_type' => 'primary',
                'parent_id' => null,
                'url' => '/contact',
                'route_name' => null,
                'description' => 'Contact information',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 4,
                'icon' => 'fa fa-envelope',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert primary menu items and get their IDs
        $insertedPrimaryMenus = [];
        foreach ($primaryMenus as $menu) {
            $insertedPrimaryMenus[$menu['title']] = Menu::create($menu);
        }

        // About Us Submenu Items
        $aboutSubmenus = [
            [
                'title' => 'Organization Profile',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['About Us']->id,
                'url' => '/about/organization-profile',
                'route_name' => null,
                'description' => 'Our organization profile',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 1,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Our Vision',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['About Us']->id,
                'url' => '/about/vision',
                'route_name' => null,
                'description' => 'Our company vision',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 2,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Our Mission',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['About Us']->id,
                'url' => '/about/mission',
                'route_name' => null,
                'description' => 'Our company mission',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 3,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Team',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['About Us']->id,
                'url' => '/about/team',
                'route_name' => null,
                'description' => 'Our team members',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 4,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Services Submenu Items
        $servicesSubmenus = [
            [
                'title' => 'E-Card',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['Services']->id,
                'url' => '/services/e-card',
                'route_name' => null,
                'description' => 'Digital card services',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 1,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'On Demand Service',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['Services']->id,
                'url' => '/services/on-demand-service',
                'route_name' => 'frontend.services.on-demand-service',
                'description' => 'On-demand services',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 2,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Market Place',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['Services']->id,
                'url' => '/services/marketplace',
                'route_name' => null,
                'description' => 'Online marketplace',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 3,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Education',
                'menu_type' => 'primary',
                'parent_id' => $insertedPrimaryMenus['Services']->id,
                'url' => '/services/education',
                'route_name' => null,
                'description' => 'Educational services',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 4,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert submenu items
        foreach ($aboutSubmenus as $submenu) {
            Menu::create($submenu);
        }

        foreach ($servicesSubmenus as $submenu) {
            Menu::create($submenu);
        }

        // Footer Menu Items
        $footerMenus = [
            [
                'title' => 'Privacy Policy',
                'menu_type' => 'footer',
                'parent_id' => null,
                'url' => '/privacy-policy',
                'route_name' => null,
                'description' => 'Privacy policy page',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 1,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Terms of Service',
                'menu_type' => 'footer',
                'parent_id' => null,
                'url' => '/terms-of-service',
                'route_name' => null,
                'description' => 'Terms of service page',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 2,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'FAQ',
                'menu_type' => 'footer',
                'parent_id' => null,
                'url' => '/faq',
                'route_name' => null,
                'description' => 'Frequently asked questions',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 3,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Support',
                'menu_type' => 'footer',
                'parent_id' => null,
                'url' => '/support',
                'route_name' => null,
                'description' => 'Member support',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 4,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'About Us',
                'menu_type' => 'footer',
                'parent_id' => null,
                'url' => '/about',
                'route_name' => null,
                'description' => 'About our company',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 5,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Contact',
                'menu_type' => 'footer',
                'parent_id' => null,
                'url' => '/contact',
                'route_name' => null,
                'description' => 'Contact us',
                'is_active' => true,
                'open_in_new_tab' => false,
                'sort_order' => 6,
                'icon' => '',
                'css_class' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert footer menu items
        foreach ($footerMenus as $menu) {
            Menu::create($menu);
        }

        $this->command->info('Menu seeder completed successfully!');
        $this->command->info('Created '.count($primaryMenus).' primary menu items');
        $this->command->info('Created '.(count($aboutSubmenus) + count($servicesSubmenus)).' submenu items');
        $this->command->info('Created '.count($footerMenus).' footer menu items');
    }
}
