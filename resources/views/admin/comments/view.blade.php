@extends('admin.layouts.main')
@section('title', 'View Comment')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Comment /</span> All Comment /</span> View Comment
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Comment Setting</h5>
                    <!-- Account -->
                    <hr class="my-0" />
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{ $Comment['image'] ? asset($Comment['image']) : asset('assets/admin/img/avatars/dummy-image-square.jpg') }}"
                                    alt="Blog Image" class="d-block rounded" height="100" width="100"
                                    id="uploadedAvatar" />
                                <div id="dvPreview">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control" type="text" disabled id="name" name="name"
                                    value="{{ $Comment['name'] }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="text" disabled id="email" name="email"
                                    value="{{ $Comment['email'] }}" />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="blog" class="form-label">Blog</label>
                                <a href="{{ route('admin.blogs.view', ['id' => $Comment->blog->id]) }}">
                                    <div class="form-control" id="blog">{{ $Comment->blog->title }}</div>
                                </a>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="name" class="form-label">Comment</label>
                                <textarea class="form-control" disabled name="comment" id="comment" rows="3">{{ $Comment['comment'] }}</textarea>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.comments.edit', $Comment->id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@stop
