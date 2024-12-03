@extends('layouts.PostBlug_header')
@section('content')
@section('title', __('Post Blug'))

@if (!isset($is_owner))
    @php
        $is_owner = false;
    @endphp
@endif

<body>
    <main>
        <div class="container" style="width: 80%;">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-9 col-xl-8 board shadow-lg rounded bg-light p-4 ">

                    <!-- Profile Media -->
                    <div>
                        @include('partials.ProfileMedia')
                    </div>

                    <!-- Descriptions -->
                    <div>
                        @include('partials.Descriptions')
                    </div>

                    <!-- switch button -->
                    @if ($is_owner)

                        <div class="switch-btn">
                            <p>let people post on your profile!!</p>

                            <div class="switch-btn-img">
                                @if($privacy_on)
                                    <img id="switch-on-btn" class="icon6" src="{{ asset('/PostBlug/switch-on-icon.png') }}" alt="Switch On" >
                            @else
                                    <img id="switch-off-btn" class="icon6" src="{{ asset('/PostBlug/switch-off-icon.png') }}" alt="Switch Off" >
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Create Post -->
                    @if ($is_owner)
                        <div class="mb-4">
                            @include('partials.createPost')
                        </div>
                    @endif

                    <!-- Filter Buttons -->
                    @if ($is_owner)
                        <div class="filter-btn d-flex justify-content-around mt-3 mb-4 ">
                            <button class="btn4 post-filter " data-status="published">Published</button>
                            <button class="btn4 post-filter " data-status="draft">Draft</button>
                            <button class="btn4 post-filter " data-status="archived">Archived</button>
                        </div>
                    @endif

                    <!-- Posts -->
                    <div class="mb-4">
                        @include('partials.posts', ['is_owner' => $is_owner])
                    </div>

                    <!-- Edit Post -->
                    @if ($is_owner)
                        <div class="mb-4">
                            <!-- At the bottom of your profile.blade.php, before the closing body tag -->
                            <template id="editPostModalTemplate">
                                <div class="modal fade" id="editPostModal" tabindex="-1"
                                    aria-labelledby="editPostModalLabel" aria-hidden="true">
                                    @include('partials.editPost')
                                </div>
                            </template>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>

    <script>

        $('#editPostModal').on('hidden.bs.modal', function () {
            $('#mediaCarousel .carousel-inner').empty(); // Clear the carousel content

    // Reset any other modal-specific state if needed
    $('#editPostForm')[0].reset(); // Reset the form inside the modal
    $('#selectedCategoryId').val('');
        $('.category-item').removeClass('selected');
    });



        $('#switch-on-btn').on('click', function() {
            $.ajax({
                url: '{{ route('profile.switch-privacy') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    privacy_on: 0
                },
                success: function(response) {
                    console.log(response);
                    let privacy=`
                    <div class="switch-btn-img">
                            <div>no one can post on your profile!!</div>
                            <img id="switch-off-btn" class="icon6" src="{{ asset('/PostBlug/switch-off-icon.png') }}" alt="Switch Off" >
                        </div>
                    `;
                    $('.switch-btn').html(privacy);
                    Swal.fire({
                        title: "people can post on your profile now!",
                        icon: "success"
                    });
                },
            });
        });

        $('#switch-off-btn').on('click', function() {
            $.ajax({
                url: '{{ route('profile.switch-privacy') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    privacy_on: 1
                },
                success: function(response) {
                    console.log(response);
                    let privacy=`
                    <div class="switch-btn-img">
                            <div>no one can post on your profile!!</div>
                            <img id="switch-on-btn" class="icon6" src="{{ asset('/PostBlug/switch-on-icon.png') }}" alt="Switch On" >
                        </div>
                    `;
                    $('.switch-btn').html(privacy);
                    Swal.fire({
                        title: "no one can post on your profile now!",
                        icon: "success"
                    });
                },
            });

        });

        // Handle form submission via AJAX
        $('#postForm').on('submit', function(e) {
            e.preventDefault();

            // Validate category again during form submission
            var categoryId = $('#selectedCategoryId').val();
            if (!categoryId) {
                Swal.fire({
                    title: "Error",
                    text: "Please select a category before submitting",
                    icon: "error"
                });
                return false;
            }

            if (!validateMediaCount()) {
                return false;
            }

            var formData = new FormData(this);

            // Append multiple photos
            var photos = $('#photo')[0].files;
            for (let i = 0; i < photos.length; i++) {
                formData.append('photos[]', photos[i]);
            }

            // Append multiple videos
            var videos = $('#video')[0].files;
            for (let i = 0; i < videos.length; i++) {
                formData.append('videos[]', videos[i]);
            }

            formData.append('status', clickedStatus);
            formData.append('category_id', categoryId);
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: '{{ route('posts.store') }}',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#post-list').html(response.data.html);
                    $('#postForm')[0].reset();
                    loadCategories(null);

                    if (response.success) {
                        Swal.fire({
                            title: "Post created successfully!",
                            icon: "success"
                        });

                        $('#newPostModal').hide();
                        // Remove modal backdrop manually
                        $('.modal-backdrop').remove();
                        // Restore body scrolling
                        $('body').removeClass('modal-open').css('overflow', '');
                        $('body').css('padding-right', '');

                        // Reinitialize the create post button
                        $('#toggleFormButton').off('click').on('click', function() {
                            $('#newPostModal').toggle();
                        });

                        initializePostHandlers(); // Reinitialize handlers after creating post

                    } else {
                        Swal.fire({
                            title: "Error",
                            text: 'Error: ' + response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error",
                        text: 'Error creating post',
                        icon: "error"
                    });
                },
                complete: function() {
                    window.loadEndInitialized = true;
                }

            });
        });

        // Filter posts by status
        $('.post-filter').on('click', function() {
            console.log('clicked');

            // Reset pagination and loading states
            window.postLoader.currentPage = 1;
            window.postLoader.hasMorePages = true;
            window.postLoader.isLoading = false;

            // Remove active class from all buttons
            $('.post-filter').removeClass('active');
            // Add active class to clicked button
            $(this).addClass('active');

            var status = $(this).data('status');

            $.ajax({
                url: '{{ route('posts.filter') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        // Update the posts list with new content
                        $('#post-list').html(response.html);
                        initializePostHandlers();
                        window.loadEndInitialized = true;
                        $('.post-filter.active').removeClass('active');
                        $('.post-filter[data-status="published"]').addClass('active');

                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error",
                        text: 'Error filtering posts',
                        icon: "error"
                    });
                    console.error(xhr.responseText);
                },
            });
        });


        // Function to initialize post handlers
        function initializePostHandlers() {
            // Reinitialize edit buttons
            $('.edit-post-btn').each(function() {
                initializeEditButton(this);
            });

            // Reinitialize archive buttons
            $('.archive-post-btn').each(function() {
                initializeArchiveButton(this);
            });

            // Reinitialize carousels
            $('.carousel').each(function() {
                new bootstrap.Carousel(this);
            });


        }
        // Toggle form visibility
        $(document).on('click', '#toggleCommentForm', function() {
            var postId = $(this).data('id');
            $('#commentForm-' + postId).toggle();
            $('#commentForm2-' + postId).toggle();


            // Hide other comment forms
            $('.commentForm').not('#commentForm-' + postId).hide();
            $('.commentForm').not('#commentForm2-' + postId).hide();
        });
        // Helper function for asset URL
        function asset(path) {
            return '{{ url('/') }}' + path;
        }

        // Delete Post
        $(document).on('click', '.delete-post-btn', function(e) {
            e.preventDefault();
            var postId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/posts/${postId}/delete`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            if (response.success) {
                                $('#post-list').html(response.data.html);
                                Swal.fire({
                                    title: "Post deleted successfully!",
                                    icon: "success"
                                });
                                $('.post-filter.active').removeClass('active');
                                $('.post-filter[data-status="published"]').addClass('active');
                                initializePostHandlers
                            (); // Reinitialize handlers after deleting post
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error",
                                text: 'Error deleting post',
                                icon: "error"
                            });
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: 'Post not deleted',
                        icon: "error"
                    });
                }
            });
        });

        // Publish Post
        $(document).on('click', '.publish-post-btn', function(e) {
            e.preventDefault();
            var postId = $(this).data('id');
            var status = $(this).data('status');
            console.log(postId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to publish this post?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, publish it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/posts/${postId}/publish`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#post-list').html(response.data.html);
                                Swal.fire({
                                    title: "Post published successfully!",
                                    icon: "success"
                                });
                                $('.post-filter.active').removeClass('active');
                                $('.post-filter[data-status="published"]').addClass('active');
                                initializePostHandlers
                            (); // Reinitialize handlers after publishing post
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error",
                                text: 'Error publishing post',
                                icon: "error"
                            });
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Cancelled",
                        text: 'Post not published',
                        icon: "info"
                    });
                }
            });
        });



        window.postLoader = {
            currentPage: 1,
            isLoading: false,
            hasMorePages: true,

            init: function() {
                if (!window.loadEndInitialized) {
                    this.attachScrollHandler();
                    this.initializePostHandlers();
                    window.loadEndInitialized = true;
                }
            },

            loadMorePosts: function(retryCount = 0) {
                if (this.isLoading || !this.hasMorePages) {
                    if (!this.hasMorePages) {
                        console.log('No more posts');
                    }
                    return;
                }

                this.isLoading = true;

                const home = @json($home);
                const is_owner = @json($is_owner);
                let status = $('.post-filter.active').data('status');
                if (typeof status === 'undefined') {
                    status = 'published';
                }
                console.log(status);
                $.ajax({
                    url: '{{ route('posts.load-more') }}',
                    type: 'GET',
                    data: {
                        page: this.currentPage + 1,
                        home: home,
                        status: status,
                        is_owner: is_owner
                    },
                    success: (response) => {
                        if (response.data.html.trim() === '') {
                            this.hasMorePages = false;
                            console.log('no more pages');
                            $('#loading-spinner').hide();

                        } else {
                            this.currentPage++;

                            $('#post-list').append(response.data.html);
                            this.hasMorePages = response.data.hasMorePages;
                            this.initializePostHandlers();

                        }

                        if (!this.hasMorePages) {
                            $('#loading-spinner').hide();
                        }
                    },
                    error: (xhr) => {
                        console.error('Error loading posts:', xhr);

                        if (retryCount < 3) {
                            setTimeout(() => {
                                this.isLoading = false;
                                this.loadMorePosts(retryCount + 1);
                            }, 1000 * (retryCount + 1));
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: 'Failed to load more posts. Please try again later.',
                                icon: "error"
                            });
                        }
                    },
                    complete: () => {
                        $('#loading-spinner').remove();
                        this.isLoading = false;
                    }
                });
            },

            debounce: function(func, wait) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            },

            attachScrollHandler: function() {
                $(window).scroll(this.debounce(() => {
                    if (!this.hasMorePages) {
                        $('#loading-spinner').hide();
                        return;
                    }

                    if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        this.loadMorePosts();
                    }
                }, 250));
            },

            initializePostHandlers: function() {
                // Initialize edit buttons
                $('.edit-post-btn').each(function() {
                    if (!$(this).data('initialized')) {
                        initializeEditButton(this);
                        $(this).data('initialized', true);
                    }
                });

                // Initialize archive buttons
                $('.archive-post-btn').each(function() {
                    if (!$(this).data('initialized')) {
                        initializeArchiveButton(this);
                        $(this).data('initialized', true);
                    }
                });
            }


        };



        function initializeEditButton(button) {
            $(button).off('click').on('click', function(e) {
                e.preventDefault();

                // First, ensure the modal exists by checking the original modal
                var editModal = $('#editPostModal');

                // If modal doesn't exist, clone it from the template
                if (editModal.length === 0) {
                    // Try to find the modal template
                    var modalTemplate = $('#editPostModalTemplate');
                    if (modalTemplate.length > 0) {
                        $('body').append(modalTemplate.html());
                        editModal = $('#editPostModal');
                    } else {
                        console.error('Edit Post Modal template not found');
                        return;
                    }
                }

                var postId = $(this).data('id');
                var description = $(this).data('description');
                var category = $(this).data('category');
                var media = $(this).data('media');



                // Set values in the edit modal
                $('#post-id').val(postId);
                $('#edit-description').val(description);
                $('#edit-category').val(category);

                // Clear existing carousel items
                $('.carousel-inner').empty();

                // Update carousel with media
                if (media && media.length > 0) {
                    media.forEach(function(item, index) {
                        var slideHtml = `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}" data-media-id="${item.id}">
                        <div class="position-relative">`;
                        if (item.type === 'post_image') {
                            slideHtml += `
                        <img src="{{ asset('storage/photos/') }}/${item.URL}"
                             alt="Post Photo"
                             class="d-block w-100"
                             style="max-width: 150%; height: 500px; object-fit: cover;">`;
                        } else if (item.type === 'post_video') {
                            slideHtml += `
                        <video class="d-block w-100" controls
                               style="max-width: 150%; height: 500px; object-fit: cover;">
                            <source src="{{ asset('storage/videos/') }}/${item.URL}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>`;
                        }
                        slideHtml += `
                    </div>
                </div>`;

                        $('.carousel-inner').append(slideHtml);
                    });

                    // Show/hide carousel controls based on media count
                    if (media.length > 1) {
                        $('.carousel-control-prev, .carousel-control-next').show();
                    } else {
                        $('.carousel-control-prev, .carousel-control-next').hide();
                    }
                } else {
                    // If no media, hide controls and show placeholder
                    $('.carousel-control-prev, .carousel-control-next').hide();
                    $('.delete-media-btn').hide();
                    $('.carousel-inner').html('<div class="text-center p-3">No media available</div>');
                }

                // Initialize carousel
                var carousel = new bootstrap.Carousel(document.querySelector('#mediaCarousel'), {
                    interval: false // Prevent auto-sliding
                });

                // Show the modal
                $('#editPostModal').modal('show');
            });
        }





        function initializeArchiveButton(button) {
            $(button).on('click', function(e) {
                e.preventDefault();
                var postId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, archive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/posts/${postId}/archive`,
                            type: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Update the posts list with new HTML
                                    $('#post-list').html(response.data.html);

                                    // Reinitialize buttons for the updated posts
                                    initializePostHandlers();

                                    $('.post-filter.active').removeClass('active');
                                    $('.post-filter[data-status="published"]').addClass(
                                        'active');

                                    Swal.fire({
                                        title: "Post archived successfully!",
                                        icon: "success"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: response.message ||
                                            'Error archiving post',
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error",
                                    text: 'Error archiving post',
                                    icon: "error"
                                });
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        }



        // Initialize when document is ready
        $(document).ready(function() {
            window.postLoader.init();
        });








        // Toggle form visibility
        $('#toggleFormButton').click(function() {
            $('#newPostModal').toggle();
        });


        // Capture the clicked button's status and validate category
        var clickedStatus = null;
        var categoryId = $('#selectedCategoryId').val();
        $('#postForm button[type="submit"]').click(function(e) {
            e.preventDefault(); // Prevent form submission initially

            // Check if category is selected
            var categoryId = $('#selectedCategoryId').val();
            if (!categoryId) {
                Swal.fire({
                    title: "Error",
                    text: 'Please select a category before submitting',
                    icon: "error"
                });
                return false;
            }

            clickedStatus = $(this).data('status'); // Get the status from the button clicked
            $('#postForm').submit(); // Submit the form if validation passes
        });


        function validateMediaCount() {
            const photos = $('#photo')[0].files;
            const videos = $('#video')[0].files;
            const totalCount = photos.length + videos.length;

            if (totalCount > 5) {
                Swal.fire({
                    title: "Error",
                    text: 'You cannot upload more than 5 media files in total.',
                    icon: "error"
                });
                // Clear file inputs
                $('#photo').val('');
                $('#video').val('');
                return false;
            }
            return true;
        }

        // Add validation to file inputs change
        $('#photo, #video').on('change', validateMediaCount);




        // Handle the "Save changes" button click
        $('#saveChangesBtn').on('click', function() {
            console.log('saveChangesBtn clicked');
            var formData = new FormData($('#editPostForm')[0]);
            var postId = $('#post-id').val();
            var description = $('#edit-description').val();
            var status = $('#status').val();
            var category_id = $('#edit-category').val();

            formData.append('post_id', postId);
            formData.append('description', description);
            formData.append('status', status);
            formData.append('category_id', category_id);

            if (!validateUpdateMediaCount(postId)) {
                $('#edit-photos').val('');
                $('#edit-videos').val('');
                return false;
            }
            if($('#mediaCarousel .carousel-item').length){
                $('.delete-media-btn').show();

            }

            $.ajax({
                url: '/posts/' + postId + '/update',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data.haveMedia);
                        $('#post-list').html(response.data.html);

                        // Close modal and show success message
                        $('#editPostModalTemplate').hide();
                        $('#delete-media').hide();
                        Swal.fire({
                            title: "Post updated successfully!2",
                            icon: "success"
                        });

                        // Remove modal backdrop manually
                        $('.modal-backdrop').remove();
                        // Restore body scrolling
                        $('body').removeClass('modal-open').css('overflow', '');
                        $('body').css('padding-right', '');
                        initializePostHandlers(); // Reinitialize handlers after updating post
                    } else {
                        Swal.fire({
                            title: "Error updating post!",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error updating post!",
                        icon: "error"
                    });
                }
            });
        });


        // Add validation to file inputs change
        $('#edit-photo, #edit-video').on('change', function() {
            const postId = $('#post-id').val();
            validateUpdateMediaCount(postId);
        });


        // Delete Media
        $(document).on('click', '.delete-media-btn', function(e) {
            e.preventDefault();
            // var mediaId = $(this).closest('.carousel-item').find('[data-media-id]').data('media-id');

            var editButton = $(this).closest('.board').find('.edit-post-btn');

            var activeItem = $('.carousel-inner .carousel-item.active');
            var mediaId = activeItem.data('media-id');

    if (!mediaId) {
        Swal.fire('Error!', 'No media selected for deletion.', 'error');
                return;
            }
            console.log(mediaId);
            // console.log(editButton);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/posts/media/${mediaId}/delete`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update the edit button's data attributes with new data
                                editButton
                                    .data('id', response.data.id)
                                    .data('description', response.data.description)
                                    .data('category', response.data.category_id)
                                    .data('media', response.data.media);

                                // Update the modal form fields
                                $('#post-id').val(response.data.id);
                                $('#edit-description').val(response.data.description);
                                $('#edit-category').val(response.data.category_id);

                                // Update carousel with new media data
                                updateCarouselMedia(response.data.media);

                                Swal.fire({
                                    title: "Media deleted successfully!",
                                    icon: "success"
                                });
                                initializePostHandlers(); // Reinitialize handlers after deleting media
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error",
                                text: 'Error deleting media',
                                icon: "error"
                            });
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        // Update carousel with media
        function updateCarouselMedia(media) {

            // Clear existing carousel items
            $('.carousel-inner').empty();

            if (media && media.length > 0) {
                media.forEach(function(item, index) {
                    var slideHtml = `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                <div class="position-relative">`;

                    if (item.type === 'post_image') {
                        slideHtml += `<img src="${asset('/storage/photos/')}/${item.URL}"
                 alt="Post Photo"
                 class="d-block w-100"
                 style="max-width: 150%; height: 500px; object-fit: cover;">`;
                    } else if (item.type === 'post_video') {
                        slideHtml += `<video class="d-block w-100" controls
                       style="max-width: 150%; height: 500px; object-fit: cover;">
                    <source src="${asset('/storage/videos/')}/${item.URL}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>`;
                    }

                    slideHtml += `
            </div>
            </div>`;

                    $('.carousel-inner').append(slideHtml);
                });

                // Show/hide carousel controls based on media count
                if (media.length > 1) {
                    $('.carousel-control-prev, .carousel-control-next').show();
                } else {
                    $('.carousel-control-prev, .carousel-control-next').hide();
                }

                // Reinitialize carousel
                var carousel = new bootstrap.Carousel(document.querySelector('#mediaCarousel'), {
                    interval: false // Prevent auto-sliding
                });
            } else {
                // If no media, hide controls and show placeholder
                $('.carousel-control-prev, .carousel-control-next').hide();
                $('.carousel-inner').html('<div class="text-center p-3">No media available</div>');
            }
        }

        function validateUpdateMediaCount(postId) {
            const photos = $('#edit-photos')[0]?.files || [];
            const videos = $('#edit-videos')[0]?.files || [];
            const existingMediaCount = $('#mediaCarousel .carousel-item').length;
            const newMediaCount = photos.length + videos.length;
            const totalCount = existingMediaCount + newMediaCount;

            if (totalCount > 5) {
                Swal.fire({
                    title: "Error",
                    text: 'You cannot have more than 5 media files in total.',
                    icon: "error"
                });
                // Clear file inputs
                $('#edit-photo').val('');
                $('#edit-video').val('');
                return false;
            }
            return true;
        }

        let selectedCategoryId = null;

        function loadCategories(parentId = null) {
            $.ajax({
                url: parentId ?
                    `{{ route('categories.nested', ['id' => ':parentId']) }}`.replace(
                        ':parentId', parentId) : '{{ route('categories.index') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    let categoryHtml = '';
                    // Check if response exists and has data
                    if (response.data && Array.isArray(response.data)) {
                        if (parentId) {
                            categoryHtml += `
                                    <li class="list-group-item category-item back-category"
                                        data-category-id="${response.data.parent_id}"
                                        data-has-children="false">
                                        <span id="backButton" class="float-start">< Back</span>
                                    </li>
                                `;
                        }
                        response.data.forEach(function(category) {
                            categoryHtml += `
                                    <li class="list-group-item category-item"
                                        data-category-id="${category.id}"
                                        data-has-children="${category.has_children ? true : false}">
                                        ${category.name}
                                        ${category.has_children ? '<span class="float-end">></span>' : ''}
                                    </li>
                                `;
                        });
                    } else {
                        categoryHtml = '<li class="list-group-item">No categories found</li>';
                    }

                    $('#categoryList').html(categoryHtml);
                },
                error: function(xhr) {
                    console.error('Error loading categories:', xhr);
                    $('#categoryList').html(
                        '<li class="list-group-item">Error loading categories</li>');
                }
            });
        }

        $(document).on('click', '.category-item', function() {
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).text().trim();

            selectedCategoryId = categoryId;
            $('#selectedCategoryId').val(selectedCategoryId);
            $('#selectedCategoryName').text(categoryName);

            if ($(this).data('has-children')) {
                loadCategories(categoryId);
            }

            // Highlight selected category
            $('.category-item').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document).on('click', '#backButton', function() {
            loadCategories(null);
            $('.delete-media-btn').hide();
        });

        $('#selectCategory').click(function() {
            if (selectedCategoryId) {
                $('#selectedCategoryId').val(selectedCategoryId);
                $('#categoryButton').text($('#selectedCategoryName').text());
                $('#categoryModal').modal('hide');
                // Remove modal backdrop manually
                $('.modal-backdrop').remove();
                // Restore body scrolling
                $('body').removeClass('modal-open').css('overflow', '');
                $('body').css('padding-right', '');
            }
        });

        $('#categoryModal').on('show.bs.modal', function() {
            loadCategories();
        });

        $('#categoryModal').on('hidden.bs.modal', function() {
            $('#categoryList').html('');
            $('#categoryButton').removeClass('btn-success').addClass('btn-primary');
            // Remove modal backdrop and restore body state
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
            $('body').css('padding-right', '');
        });

        // Update category button text when category is selected
        $(document).on('click', '.category-item', function() {
            if (!$(this).data('has-children')) {
                const categoryName = $(this).text().trim();
                $('#selectedCategoryName').text(categoryName).show();
                $('#categoryButton').addClass('btn-success').removeClass('btn-primary');
            }
        });
    </script>
@endsection
</body>
