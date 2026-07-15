@extends('layouts.backend.app')

@section('title', 'Attribute Values - ' . $attribute->name)

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
        <span class="role">eCommerce</span>
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
        </form>
      </li>
    </ul>
  </div>
  <h4>Attribute Values: {{ $attribute->name }}</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.attributes.values.create', $attribute) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Value</a>
    <a href="{{ route('admin.attributes.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Back to Attributes</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered" style="border-color: #a1a1a1 !important;">
    <thead>
      <tr>
        <th>#</th>
        <th>Value</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($values as $value)
        <tr>
          <td>{{ $value->id }}</td>
          <td>{{ $value->value }}</td>
          <td>
            <a href="{{ route('admin.attributes.values.edit', [$attribute, $value]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.attributes.values.destroy', [$attribute, $value]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $values->links() }}
</div>
@endsection