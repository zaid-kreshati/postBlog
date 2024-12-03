@if (!isset($is_owner))
    @php
        $is_owner = false;
    @endphp
@endif

<div id="post-list" style="width: 100%;">

    @foreach ($post_list as $post)
        @if ($post->owner_id != $user_id && $status != 'published')
            @continue
        @endif
        <tr id="post-{{ $post->id }}">
            @if (!$home)
                @php
                    $isUserPost = false;
                    foreach ($post->tag as $tag) {
                        if ($tag->user_id == $user_id) {
                            $isUserPost = true;
                            break;
                        }
                    }
                @endphp

                @if (!$isUserPost)
                    @continue
                @endif
            @endif


            <div class="board shadow-lg"
                style="    position: relative;
                    align-content: center;
                    left: -22.8px;
                    width: 106.9%;">
                @foreach ($post->tag as $tag)
                    @if ($tag->user_id == $post->owner_id)
                        <!-- Profile Image -->
                        @if ($tag->user->media->isNotEmpty())
                            @foreach ($tag->user->media as $media)
                                    @if ($media->type == 'user_profile_image')
                                        <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                            class="img-fluid rounded-circle"
                                            data-user-id="{{ $tag->user_id }}"
                                            style="width: 90px; height: 90px; object-fit: fill;
                                            top: 10px; left: 20px; position: absolute;
                                            cursor: pointer;">
                                    @endif
                                @endforeach
                            @else
                                <img src="{{ asset('/PostBlug/default-profile.png') }}" alt="Profile photo"
                                    class="img-fluid rounded-circle"
                                    style="width: 90px; height: 90px; object-fit: fill; margin-right: 20px;">
                            @endif



                            <div style="font-size: 35px; left: 115px; position: absolute;">
                                {{ $tag->user->name }}

                            </div>
                    @endif
                @endforeach
                <div style="margin-bottom: 115px;"></div>


                <!-- Post Description -->
                <div class="text-start mt-2 mb-3">
                    <h2 style="margin: 0; text-align: left; padding-left: 10px;">{{ $post->description }}
                    </h2>
                </div>





                <img src="{{ asset('PostBlug/three-dots-icon.png') }}"
                alt="More" class="icon5 dropdown-toggle"
                    id="dropdownMenuButton"
                    style="position: absolute; right: 10px; top: 10px; cursor: pointer;"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">


                <!-- Post Media -->
                @if ($post->media->isNotEmpty())
                    @if ($post->media->count() > 1)
                        <!-- Show carousel for multiple media items -->
                        <div id="postMediaCarousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($post->media as $key => $media)
                                    <!-- Image Slide -->
                                    @if ($media->type == 'post_image')
                                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Post Photo"
                                                class="d-block w-100"
                                                style="max-width: 250%; height: 500px; object-fit: contain;">
                                        </div>
                                    @endif

                                    <!-- Video Slide -->
                                    @if ($media->type == 'post_video')
                                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                            <video class="d-block w-100" controls
                                                style="max-width: 250%; height: 500px; object-fit: contain;">
                                                <source src="{{ asset('storage/videos/' . $media->URL) }}"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Carousel Controls -->
                            <button class="carousel-control-prev" type="button"
                                style="left: -40px; margin-top: 250px; margin-bottom: 250px;"
                                data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"
                                    style="background-color: gray;"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                style="right: -40px; margin-top: 250px; margin-bottom: 250px; "
                                data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"
                                    style="background-color: gray;"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @else
                        <!-- Show single media item without carousel -->
                        @foreach ($post->media as $media)
                            @if ($media->type == 'post_image')
                                <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Post Photo"
                                    class="d-block w-100" style="max-width: 150%; height: 500px; object-fit: contain;">
                            @endif

                            @if ($media->type == 'post_video')
                                <video class="d-block w-100" controls
                                    style="max-width: 150%; height: 500px; object-fit: contain;">
                                    <source src="{{ asset('storage/videos/' . $media->URL) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        @endforeach
                    @endif
                @endif


                <hr>
                <div style="margin-top: 50px;"></div>
                @include('partials.edit-post-status')

                    <!-- Comment Button -->
                    <label  class="comment-label"  >{{"comment"}}</label>
                    <img src="{{ asset('PostBlug/comment-icon.png') }}"id="toggleCommentForm"
                    for="toggleCommentForm" data-id="{{ $post->id }}"
                     alt="Comment" class="icon4" style="right: 20px; bottom: 10px; position: absolute;" >



                    {{-- <div class="commentFormContainer" id="commentForm-{{ $post->id }}">
                                @include('partials.comment', ['post' => $post])
                            </div> --}}
                </div>
                @include('partials.comment')




            </div>
            <div style="margin-top: 20px;">

        </tr>
    @endforeach


    <!-- Loading Spinner -->
    <div id="loading-spinner" class="text-center" style="display: content;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>


</div>

<script>
    $(document).on('click', '.comment-btn', function() {
        var postId = $(this).data('id');

    });





    $(document).off('keydown', 'textarea[name="text"]').on('keydown', 'textarea[name="text"]', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            console.log("enter");
            var postId = $(this).closest('form').find('input[name="post_id"]').val();
            var text = $(this).val();

            if (!postId || !text.trim()) {
                Swal.fire({
                    title: "Error",
                    text: 'Post ID or comment text is missing.',
                    icon: "error"
                });
                return;
            }

            let formData = {
                _token: '{{ csrf_token() }}',
                post_id: postId,
                text: text
            };

            $.ajax({
                url: '{{ route('comment.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        let html = `
                            <div class="comments-section">
                                <div class="comment">
                                    <div class="comment-header">
                                        ${response.data.personal_image ?
                                            `<img src="{{ asset('storage/photos/') }}/${response.data.personal_image.URL}"
                                                 alt="Profile photo"
                                                 class="img-fluid rounded-circle"
                                                  style="width: 60px; height: 60px; object-fit: fill;
                                                  margin-right: 760px;">` :
                                            `<img src="{{ asset('/PostBlug/default-profile.png') }}"
                                                 alt="Profile photo"
                                                 class="img-fluid rounded-circle"
                                                 style="width: 60px; height: 60px; object-fit: fill;
                                                 margin-right: 760px;">`
                                        }
                                        <div style="right: 750px; font-size: 25px; margin-top: -40px; position: relative;">
                                            ${response.data.name}
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${response.data.comment.text}
                                    </div>
                                    <div class="comment-actions" style="right: 5%;">
                                        <button class="comment-btn reply-btn" id="replyCommentBtn" data-comment-id="${response.data.comment.id}" >
                                            <i class="fas fa-reply"></i>
                                            <div>{{ __('Reply') }}</div>
                                        </button>

                                        <form action="{{ route('comment.store.nested') }}" method="POST" id="replyCommentForm-${response.data.comment.id}"
                                            class="comment-form" style="display: none;">
                                            @csrf
                                            <div class="nested-comment-form">

                                                </div>

                                            <label for="nested-comment">{{ __('Reply') }}:</label>
                                                <input type="hidden" name="parent_id" id="comment-id" value="${response.data.comment.id}">
                                                <input type="hidden" name="post_id" id="post-id" value="${response.data.comment.id}">
                                                <textarea name="nested_text" class="comment-input"
                                                    placeholder="Write a comment..."
                                                    id="nested-comment-${response.data.comment.id}"
                                                    rows="2">{{ old('nested_text') }}</textarea>
                                            </form>
                                    </div>

                                    <div class="comment-replies"></div>
                                </div>
                            </div>`;

                        $('#commentForm-' + postId).append(html);
                        $('textarea[id="comment-textarea->' + postId + '"]').val('');
                        $(this).val('');
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: 'Error submitting comment: ' + response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error",
                        text: 'Error submitting comment. Please try again.',
                        icon: "error"
                    });
                }
            });
        }
    });





    $(document).off('click', '#replyCommentBtn').on('click', '#replyCommentBtn', function() {
        console.log("reply");
        var commentId = $(this).data('comment-id');
        $('#replyCommentForm-' + commentId).toggle();
    });

    $(document).off('keydown', 'textarea[name="nested_text"]').on('keydown', 'textarea[name="nested_text"]', function(
    e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            var commentId = $(this).closest('form').find('input[name="parent_id"]').val();
            var postId = $(this).closest('form').find('input[name="post_id"]').val();
            var text = $(this).val();

            var formData = {
                _token: '{{ csrf_token() }}',
                parent_id: commentId,
                post_id: postId,
                text: text
            };
            console.log(formData);

            $.ajax({
                url: '{{ route('comment.store.nested') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        console.log(response.data);

                        let html = `
                            <div class="comments-section" id="nested-comment">
                                <div class="comment">
                                    <div class="comment-header">
                                        ${response.data.personal_image ?
                                            `<img src="{{ asset('storage/photos/') }}/${response.data.personal_image.URL}"
                                                    alt="Profile photo"
                                                    class="img-fluid rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">` :
                                            `<img src="{{ asset('/PostBlug/default-profile.png') }}"
                                                alt="Profile photo"
                                                class="img-fluid rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">`
                                        }
                                        <div style="right: 750px; font-size: 15px; margin-top: -40px; position: relative;">
                                            ${response.data.name}
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${response.data.comment.text}
                                    </div>
                                </div>
                            </div>
                        `;

                        // Insert comment into nested-comment-form
                        $('#nested-comments-section-' + commentId).append(html);

                        // Clear the input
                        $('textarea[id="nested-comment-' + commentId + '"]').val('');

                        // Hide the reply form
                        $(this).closest('form').hide();
                    }

                },
                error: function(xhr) {
                    console.error('Error submitting reply comment:', xhr);
                }
            });
        }
    });

    $(document).on('click', '[data-user-id]', function() {
        let userId = $(this).data('user-id');
        if (userId) {
            window.location.href = `{{ route('profile.index', ['id' => '']) }}`.replace('profile', 'profile/' +
                userId);
        } else {
            Swal.fire({
                title: "Error",
                text: 'User ID is not defined.',
                icon: "error"
            });
        }
    });
</script>
