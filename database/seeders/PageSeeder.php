<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'terms-conditions',
                'title' => 'Terms & Conditions',
                'content' => '<h3>Terms and Conditions</h3><p>Welcome to our eCommerce platform. By accessing or using this website, you agree to be bound by these terms and conditions. Please read them carefully.</p><p>We reserve the right to change, modify, or update these terms at any time without prior notice. Your continued use of the site signifies your acceptance of any changes.</p>',
            ],
            [
                'slug' => 'return-policy',
                'title' => 'Return Policy',
                'content' => '<h3>Return Policy</h3><p>We want you to be completely satisfied with your purchase. If you are not satisfied, you may return the item within 7 days of delivery for a full refund or exchange, subject to the conditions below.</p><ul><li>Items must be in original condition, unused, and with all tags attached.</li><li>Return shipping costs are the responsibility of the customer unless the item was damaged or incorrect.</li></ul>',
            ],
            [
                'slug' => 'support-policy',
                'title' => 'Support Policy',
                'content' => '<h3>Support Policy</h3><p>Our customer support team is here to assist you with any questions or concerns. You can reach out to us via email or phone during our business hours.</p><p>We aim to respond to all support requests within 24-48 business hours. Thank you for your patience.</p>',
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => '<h3>Privacy Policy</h3><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information when you use our website.</p><p>We only collect personal information that is necessary to process your orders and improve our services. We do not sell or share your information with third parties.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                ]
            );
        }
    }
}
