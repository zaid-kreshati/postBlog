<div class="search-results">
    @if (isset($users) && $users->isNotEmpty())
        @foreach ($users as $user)
            <div class="board shadow-lg mb-4">
                <div class="d-flex align-items-center p-3" data-user-id="{{ $user->id }}" style="cursor: pointer; width:100px">
                    @if ($user->media->isNotEmpty())
                        @foreach ($user->media as $media)
                            @if ($media->type == 'user_profile_image')
                                <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                    class="img-fluid rounded-circle me-3"
                                    style="width: 90px; height: 90px; object-fit: cover;">
                            @endif
                        @endforeach
                    @else
                        <img src="{{ asset('/PostBlug/default-profile.png') }}" alt="Profile photo"
                            class="img-fluid rounded-circle me-3"
                            style="width: 90px; height: 90px; object-fit: cover;">
                    @endif
                    <div class="fs-4">
                        {{ $user->name }}
                    </div>
                </div>
                <hr class="my-3">
            </div>
        @endforeach
    @endif
    @if (isset($posts) && $posts->isNotEmpty())
        @foreach ($posts as $post)
            <div class="board shadow-lg mb-4">
                <div class="d-flex align-items-center p-3" data-user-id="{{ $post->user_id }}" style="cursor: pointer; width:100px">
                    @if ($post->user->media->isNotEmpty())
                        @foreach ($post->user->media as $media)
                            @if ($media->type == 'user_profile_image')
                                <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                    class="img-fluid rounded-circle me-3"
                                    style="width: 90px; height: 90px; object-fit: cover;">
                            @endif
                        @endforeach
                    @else
                        <img src="{{ asset('/PostBlug/default-profile.png') }}" alt="Profile photo"
                            class="img-fluid rounded-circle me-3"
                            style="width: 90px; height: 90px; object-fit: cover;">
                    @endif
                    <div class="fs-4">
                        {{ $post->user->name }}
                    </div>
                </div>
                <div class="post p-3">
                       <!-- Post Description -->
                       <div class="text-start mt-2 mb-3">
                        <h2 style="margin: 0; text-align: left; padding-left: 10px;">{{ $post->description }}
                        </h2>
                    </div>
                    <div class="post-media mb-3">
                        @if ($post->media->count() > 1)
                            <div id="postMediaCarousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($post->media as $media)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            @if ($media->type == 'post_image')
                                                <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Post Photo" class="d-block w-100" style="max-width: 100%; height: 500px; object-fit: contain;">
                                            @elseif ($media->type == 'post_video')
                                                <video class="d-block w-100" controls style="max-width: 100%; height: 500px; object-fit: contain;">
                                                    <source src="{{ asset('storage/videos/' . $media->URL) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: gray;"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: gray;"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        @else
                            @foreach ($post->media as $media)
                                @if ($media->type == 'post_image')
                                    <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Post Photo" class="d-block w-100" style="max-width: 100%; height: 500px; object-fit: contain;">
                                @elseif ($media->type == 'post_video')
                                    <video class="d-block w-100" controls style="max-width: 100%; height: 500px; object-fit: contain;">
                                        <source src="{{ asset('storage/videos/' . $media->URL) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="button-group text-end">
                        <button id="toggleCommentForm" class="btn4" data-id="{{ $post->id }}">
                            Comment
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
