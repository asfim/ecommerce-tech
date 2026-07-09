<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogPost::create([
            'title' => 'Top 10 E-commerce Trends for 2026',
            'slug' => 'top-10-e-commerce-trends-for-2026',
            'summary' => 'Discover the key retail innovations, digital shifts, and customer experience trends set to redefine online shopping globally this year.',
            'body' => '<p>The e-commerce landscape is shifting faster than ever. As we advance through 2026, several key drivers are transforming how online brands interact with customers, fulfill logistics, and leverage data. Here are the top 10 trends that every online merchant should pay attention to:</p>
<h5>1. AI-Driven Personalization at Scale</h5>
<p>Hyper-personalization is no longer just about addressing users by name in an email. Modern AI platforms analyze browsing behaviors, purchasing patterns, and real-time intent to construct bespoke store visual grids and dynamic suggestions tailored for every individual shopper.</p>
<h5>2. Voice Commerce and Natural Language Processing</h5>
<p>Voice-assisted shopping is gaining massive traction as smart home systems and smartphone assistants evolve. Ensuring product descriptions are SEO-optimized for conversational voice queries is crucial for capturing this growing market segment.</p>
<h5>3. Sustainable and Circular Retail</h5>
<p>Consumers are demanding higher environmental accountability. Brands that offer circular trade-ins, low-carbon shipping alternatives, and eco-friendly packaging are seeing massive surges in customer loyalty and retention metrics.</p>
<p>Staying ahead of these trends isn\'t optional—it is a critical requirement for building a sustainable, profitable digital business model in the modern landscape.</p>',
            'is_active' => true,
            'views' => 145,
        ]);

        BlogPost::create([
            'title' => 'How to Design a Responsive Online Store',
            'slug' => 'how-to-design-a-responsive-online-store',
            'summary' => 'A comprehensive guide to mobile-first user experience design, quick page loads, and seamless checkout pipelines that drive conversions.',
            'body' => '<p>Mobile devices account for over 70% of global web traffic, making a responsive and fluid mobile layout essential for e-commerce success. Designing a successful mobile-first experience involves much more than simply scaling down columns. Here is how you can optimize your design:</p>
<h5>1. Mobile-First Layout Grid</h5>
<p>Start your layouts at the smallest viewport and scale up. Use responsive flexbox wraps and css grids to handle resizing seamlessly. This keeps your interface simple and keeps navigation clean on touchscreens.</p>
<h5>2. Optimize Media Loading</h5>
<p>Large banner images are the primary cause of slow load times. Implement next-gen image formats like WebP or AVIF, define explicit sizing properties to avoid layout shifts, and lazy-load offscreen media elements.</p>
<h5>3. Frictionless Touchscreen Controls</h5>
<p>Interactive elements must be optimized for finger taps. Buttons should maintain a minimum tap target size of 44x44 pixels, with ample surrounding padding to prevent accidental misclicks.</p>
<p>Applying these layout rules guarantees your store remains readable, lightning-fast, and accessible across all devices.</p>',
            'is_active' => true,
            'views' => 92,
        ]);

        BlogPost::create([
            'title' => 'Boosting Checkout Conversion Rates',
            'slug' => 'boosting-checkout-conversion-rates',
            'summary' => 'Learn how simple optimizations to cart layouts, payment processing, and trust indicators can drastically minimize cart abandonment.',
            'body' => '<p>Cart abandonment remains one of the largest obstacles for online retailers, with average abandonment rates hovering around 70%. Solving this leak in your sales funnel is the fastest way to increase profits without spending more on marketing.</p>
<h5>1. Simplify Checkout Step-by-Step</h5>
<p>A long, multi-page checkout flow is a major conversion killer. Opt for a single-page checkout sequence or a clearly labeled multi-step progress bar. Minimize the number of input fields required to place an order.</p>
<h5>2. Transparent Pricing and Policies</h5>
<p>Hidden shipping fees or taxes revealed at the last second are the number one reason buyers walk away. Present all costs, taxes, and shipping expenses directly on the cart page before they initiate the checkout sequence.</p>
<h5>3. Provide Diverse Payment Methods</h5>
<p>Support modern wallets alongside standard credit cards. Offering popular native mobile options like SSL Commerz, digital wallets, or Cash on Delivery drastically lowers friction and makes checkout effortless.</p>
<p>By implementing these optimizations, your store will deliver a seamless buying experience that converts browsers into loyal customers.</p>',
            'is_active' => true,
            'views' => 210,
        ]);
    }
}
