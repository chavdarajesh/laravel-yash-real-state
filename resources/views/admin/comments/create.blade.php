@extends('admin.layouts.main')
@section('title', 'Create Comment')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Comment /</span> All Comment /</span> Create Comment</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Create Comment </h5>
                    <!-- Account -->
                    <hr class="my-0" />
                    <div class="card-body">
                        <form id="form" method="POST" action="{{ route('admin.comments.save') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                    <img src="{{ asset('assets/admin/img/avatars/dummy-image-square.jpg') }}"
                                        alt="Comment Image" class="d-block rounded" height="100" width="100"
                                        id="uploadedAvatar" />
                                    <div id="dvPreview">
                                    </div>
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload Image</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" id="upload" class="account-file-input" hidden
                                                accept="image/*" name="image" onchange="readURL(this)" />
                                        </label>
                                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 4Mb</p>
                                    </div>
                                </div>
                                <div id="image_error" class="text-danger"> @error('image')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        id="name" name="name" value="{{ old('name') }}" autofocus />
                                    <div id="name_error" class="text-danger"> @error('name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="text"
                                        id="email" name="email" value="{{ old('email') }}" autofocus />
                                    <div id="email_error" class="text-danger"> @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="blog" class="form-label">Blog</label>
                                    <select class="form-select select2 @error('blog') is-invalid @enderror" id="blog"
                                        name="blog">
                                        <option disabled selected value="">Select Blog</option>
                                        @foreach ($blogs as $blog)
                                            <option value="{{ $blog->id }}" {{old('blog') == $blog->id ? 'selected' : ''}}>{{ $blog->title }}</option>
                                        @endforeach
                                    </select>
                                    <div id="blog_error" class="text-danger"> @error('blog')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="comment" class="form-label">Comment</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" name="comment" id="comment" rows="3">{{  old('comment') }}</textarea>
                                    <div id="comment_error" class="text-danger"> @error('comment')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2">Save</button>
                                    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{ asset('assets/admin/js/jquery.validate.min.js') }}"></script>
    <script>
         function readURL(input) {
            if (input.files && input.files[0]) {
                if (input.files[0].type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.querySelector("#uploadedAvatar").setAttribute("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    $('#image_error').html('Allowed JPG, GIF or PNG.')
                    $('#upload').val('');
                }
            }
        }
        $(document).ready(function() {
            $('#form').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    comment: {
                        required: true,
                    },
                    blog: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: 'This field is required',
                    },
                    email: {
                        required: 'This field is required',
                        email: 'Enter a valid email',
                    },
                    comment: {
                        required: 'This field is required',
                    },
                    blog: {
                        required: 'This field is required',
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    $('#' + element.attr('name') + '_error').html(error)
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('border border-danger');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('border border-danger');
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@stop
