@extends('backend.include.app')

@section('content')
    <div class="section-content section-dashboard-home">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">My Products</h2>
                <p class="dashboard-subtitle">
                    Manage it well and get money
                </p>
            </div>
            <div class="dashboard-content">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('product.create') }}" class="btn btn-success">Add New Product</a>
                    </div>
                </div>
                <div class="card mt-3 p-2">
                    <div class="card-body">
                        <div class="row mt-4">
                            <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Product</th>
                                        <th>Categories</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                {{ $item->id }}
                                            </td>
                                            <td>
                                                {{ $item->ProductName }}
                                            </td>
                                            <td>
                                                @php
                                                    $category = App\Models\category::where('id',$item->category_id)->first();
                                                @endphp
                                                {{ $item->category->name }}
                                            </td>
                                            <td>
                                                {{ $item->Stock }}
                                            </td>
                                            <td>
                                                <a href="{{ route('product.edit',$item->id)}}" class="btn btn-info"> Edit </a>
                                                <form action="{{ route('product.destroy',$item -> id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-warning"> Delete </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
    </div>

@endsection
