@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="wrap py-5">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4 animate-fade-in">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active fw-semibold" aria-current="page">Contact Us</li>
        </ol>
    </nav>

    <div class="text-center mb-5 animate-fade-in">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-2 fw-semibold text-uppercase tracking-wider" style="font-size: 11px; letter-spacing: 1px;">Get In Touch</span>
        <h1 class="display-5 fw-bold text-dark mb-3">We'd Love to Hear From You</h1>
        <p class="text-secondary mx-auto" style="max-width: 600px; font-size: 15px;">
            Have a question, feedback, or need help with an order? Reach out to us through any of the channels below or leave a message.
        </p>
    </div>

    <!-- Contact Info Cards Section -->
    <div class="row g-4 mb-5 justify-content-center animate-fade-in">
        <!-- Phone Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="contact-card p-4 rounded-4 shadow-sm bg-white border border-opacity-10 d-flex flex-column align-items-center text-center h-100 transition-all">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; flex-shrink: 0;">
                    <i class="bi bi-telephone-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Phone Number</h5>
                <p class="text-secondary mb-3 small flex-grow-1">Call us directly for immediate assistance</p>
                @if(!empty($companySettings['phone']))
                    <a href="tel:{{ $companySettings['phone'] }}" class="fw-semibold text-primary text-decoration-none hover-underline fs-6">{{ $companySettings['phone'] }}</a>
                @else
                    <a href="tel:+1234567890" class="fw-semibold text-primary text-decoration-none hover-underline fs-6">+1 (234) 567-890</a>
                @endif
            </div>
        </div>

        <!-- WhatsApp Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="contact-card p-4 rounded-4 shadow-sm bg-white border border-opacity-10 d-flex flex-column align-items-center text-center h-100 transition-all">
                <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; flex-shrink: 0;">
                    <i class="bi bi-whatsapp fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">WhatsApp Support</h5>
                <p class="text-secondary mb-3 small flex-grow-1">Chat with us anytime, anywhere</p>
                @if(!empty($companySettings['whatsapp']))
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $companySettings['whatsapp']);
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="fw-semibold text-success text-decoration-none hover-underline fs-6">
                        {{ $companySettings['whatsapp'] }} <i class="bi bi-box-arrow-up-right small ms-1" style="font-size: 10px;"></i>
                    </a>
                @else
                    <a href="https://wa.me/1234567890" target="_blank" class="fw-semibold text-success text-decoration-none hover-underline fs-6">
                        +1 (234) 567-890 <i class="bi bi-box-arrow-up-right small ms-1" style="font-size: 10px;"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Email Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="contact-card p-4 rounded-4 shadow-sm bg-white border border-opacity-10 d-flex flex-column align-items-center text-center h-100 transition-all">
                <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; flex-shrink: 0;">
                    <i class="bi bi-envelope-at-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Email Address</h5>
                <p class="text-secondary mb-3 small flex-grow-1">Send us an email and we will reply within 24 hours</p>
                @if(!empty($companySettings['email']))
                    <a href="mailto:{{ $companySettings['email'] }}" class="fw-semibold text-danger text-decoration-none hover-underline fs-6 text-break">{{ $companySettings['email'] }}</a>
                @else
                    <a href="mailto:info@ecommerce.com" class="fw-semibold text-danger text-decoration-none hover-underline fs-6">info@ecommerce.com</a>
                @endif
            </div>
        </div>

        <!-- Address Card -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="contact-card p-4 rounded-4 shadow-sm bg-white border border-opacity-10 d-flex flex-column align-items-center text-center h-100 transition-all">
                <div class="icon-box bg-warning bg-opacity-10 text-warning-emphasis rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; flex-shrink: 0;">
                    <i class="bi bi-geo-alt-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Office Location</h5>
                <p class="text-secondary mb-3 small flex-grow-1">Come visit our headquarters</p>
                <p class="fw-semibold text-dark mb-0 fs-6">
                    {{ $companySettings['address'] ?? '123 E-Commerce St, Suite 456' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Inquiry / Contact Form -->
    <div class="row justify-content-center animate-fade-in" style="animation-delay: 0.2s;">
        <div class="col-12 col-lg-10">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark">Send Us a Message</h3>
                    <p class="text-secondary small">Fill out the form below, and we'll get back to you shortly.</p>
                </div>

                <form id="contactForm" onsubmit="event.preventDefault(); alert('Thank you for contacting us! This is a demo form.'); this.reset();">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="name" class="form-label fw-semibold small text-secondary">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" id="name" class="form-control bg-light border-start-0 py-2" placeholder="e.g. John Doe" required style="border-color: #a1a1a1 !important;">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-semibold small text-secondary">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" id="email" class="form-control bg-light border-start-0 py-2" placeholder="e.g. john@example.com" required style="border-color: #a1a1a1 !important;">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="form-label fw-semibold small text-secondary">Subject</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-chat-left-text"></i></span>
                                <input type="text" id="subject" class="form-control bg-light border-start-0 py-2" placeholder="How can we help you?" required style="border-color: #a1a1a1 !important;">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label fw-semibold small text-secondary">Message</label>
                            <textarea id="message" class="form-control bg-light py-2" rows="5" placeholder="Write your message here..." required></textarea>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-3 shadow-sm hover-scale text-uppercase" style="letter-spacing: 0.5px;">
                                <i class="bi bi-send-fill me-2"></i>Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling & Custom Micro-Animations */
    .contact-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease;
    }
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }
    
    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
        border-radius: 12px;
        border: 0;
    }

    .hover-underline:hover {
        text-decoration: underline !important;
    }

    .hover-scale {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .hover-scale:hover {
        transform: scale(1.02);
    }

    /* Keyframes for sliding and fading in elements beautifully */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideRight {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideLeft {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    }
    .animate-slide-right {
        animation: slideRight 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    }
    .animate-slide-left {
        animation: slideLeft 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    }
</style>
@endsection
