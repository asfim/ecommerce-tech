@extends('layouts.backend.app')

@section('title', 'Homepage Settings')

@section('content')
<div class="clearfix mb-4">
  <h4>Homepage Settings</h4>
</div>

@php
  $tab = request('tab', 'hero_banners');
  $tabs = [
    'hero_banners'           => [
      'label' => 'Hero Section Banner',
      'max' => 2,
      'icon' => 'bi-image',
      'recommendation' => 'Recommended size: 394 x 260 px (Aspect ratio ~ 3:2)'
    ],
    'best_selling_banners'   => [
      'label' => 'Best Selling Banner',
      'max' => 3,
      'icon' => 'bi-stars',
      'recommendation' => 'Recommended size: 394 x 220 px (Aspect ratio ~ 16:9)'
    ],
    'new_arrivals_banner'    => [
      'label' => 'New Arrivals Banner',
      'max' => 1,
      'icon' => 'bi-bag-plus',
      'recommendation' => 'Recommended size: 394 x 250 px (Aspect ratio ~ 1.6:1 / 3:2)'
    ],
    'discounted_products_banner' => [
      'label' => 'Discounted Products',
      'max' => 1,
      'icon' => 'bi-lightning',
      'recommendation' => 'Recommended size: 285 x 200 px (Aspect ratio ~ 4:3 / 3:2)'
    ],
  ];
@endphp

<div class="row g-4">

  {{-- Left Tabs --}}
  <div class="col-md-3">
    <div class="stat-card p-0" style="overflow:hidden;">
      <div class="list-group list-group-flush rounded-3">
        @foreach($tabs as $key => $info)
          <a href="{{ route('admin.settings.homepage', ['tab' => $key]) }}"
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 px-3 {{ $tab === $key ? 'active' : '' }}">
            <i class="bi {{ $info['icon'] }}"></i>
            <span class="small fw-semibold">{{ $info['label'] }}</span>
          </a>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Right Content --}}
  <div class="col-md-9">
    @foreach($tabs as $key => $info)
      @if($tab === $key)
        <div class="stat-card">
          <h5 class="fw-bold mb-1"><i class="bi {{ $info['icon'] }} me-2 text-primary"></i>{{ $info['label'] }}</h5>
          <p class="text-muted small mb-4">Maximum {{ $info['max'] }} {{ $info['max'] > 1 ? 'images' : 'image' }} allowed for this section.</p>

          @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
          @endif

          {{-- Current Images --}}
          @php $current = $settings[$key] ?? []; @endphp
          @if(count($current) > 0)
            <div class="mb-4">
              <label class="form-label fw-semibold small">Current Images</label>
              <form method="POST" action="{{ route('admin.settings.homepage.update', $key) }}" id="delete-form-{{ $key }}">
                @csrf
                <div class="d-flex flex-wrap gap-3">
                  @foreach($current as $img)
                    <div class="position-relative">
                      <img src="{{ asset('storage/' . $img) }}" alt="Banner"
                           style="height:100px;width:160px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                      <button type="submit" name="delete_images[]" value="{{ $img }}"
                              class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-0"
                              style="width:22px;height:22px;font-size:11px;line-height:1;"
                              onclick="return confirm('Remove this image?')">
                        <i class="bi bi-x"></i>
                      </button>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          @endif

          {{-- Upload Form --}}
          @if(count($current) < $info['max'])
            <form method="POST" action="{{ route('admin.settings.homepage.update', $key) }}"
                  enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label class="form-label fw-semibold">
                  Upload {{ $info['max'] > 1 ? 'Images' : 'Image' }}
                  <span class="text-muted fw-normal small">({{ $info['max'] - count($current) }} slot(s) remaining)</span>
                </label>
                <input type="file" name="images[]" class="form-control"
                       accept="image/*"
                       {{ $info['max'] - count($current) > 1 ? 'multiple' : '' }}
                       required style="border-color: #a1a1a1 !important;">
                <div class="form-text d-flex align-items-center gap-1 mt-2 text-secondary">
                  <i class="bi bi-info-circle-fill text-primary"></i>
                  <span>Accepted: JPG, PNG, WebP. <strong>{{ $info['recommendation'] }}</strong></span>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-upload me-1"></i> Save Images
              </button>
            </form>
          @else
            <div class="alert alert-info py-2 small">
              <i class="bi bi-info-circle me-1"></i>
              Maximum images reached. Remove an existing image to upload a new one.
            </div>
          @endif
        </div>
      @endif
    @endforeach
  </div>

</div>
@endsection

@push('styles')
<style>
  .list-group-item.active {
    background-color: #1a73e8 !important;
    border-color: #1a73e8 !important;
    color: #fff !important;
  }
  .list-group-item {
    border-left: none;
    border-right: none;
    transition: background .15s;
  }
  .list-group-item:first-child { border-top: none; }
</style>
@endpush
