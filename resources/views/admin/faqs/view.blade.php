@extends('admin.layouts.main')
@section('title', 'View Faqs')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Faqs /</span> All Faqs /</span> View Faq</h4>

        <div class="row">
            <div class="col-md-12">

                <div class="card mb-4">
                    <h5 class="card-header">Faqs Setting</h5>
                    <!-- Account -->
                    <hr class="my-0" />
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input class="form-control" type="text" disabled id="title" name="title"
                                    value="{{ $Faq['title'] }}" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" rows="5" type="text" disabled id="description" name="description"
                                    value="">{{ $Faq['description'] }}</textarea>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.faqs.edit', $Faq->id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@stop
