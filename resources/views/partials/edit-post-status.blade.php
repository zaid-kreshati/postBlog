<div class="dropdown">

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

       @if (!$home && $is_owner)
        @if ($post->owner_id == $user_id)
            @if ($post->status == 'published')
            <li>

                <!-- Edit Button -->
                <button class="dropdown-item edit-post-btn" data-id="{{ $post->id }}"
                    data-description="{{ $post->description }}"
                    data-category="{{ $post->category_id }}"
                    data-media='@json($post->media)'
                    data-toggle="modal" data-target="#editPostModal">
                    Edit Post
                </button>
            </li>

            <li>
                    <!-- Archive Button -->
                    <button class="dropdown-item archive-post-btn" data-id="{{ $post->id }}">
                    Archive Post
                </button>
                </li>
            @elseif($post->status == 'draft')
                <!-- Edit Button -->
                <li>

                    <button class="dropdown-item edit-post-btn" data-id="{{ $post->id }}"
                        data-description="{{ $post->description }}"
                        data-category="{{ $post->category_id }}" data-media='@json($post->media)'
                        data-toggle="modal" data-target="#editPostModal">
                        Edit Post
                    </button>
                </li>


                <!-- Publish Button -->
                <li>

                    <button class="dropdown-item publish-post-btn" data-id="{{ $post->id }}"
                        data-description="{{ $post->description }}"
                    data-category="{{ $post->category_id }}" data-media='@json($post->media)'
                    data-toggle="modal" data-target="#editPostModal">
                        Publish Post
                    </button>
                </li>

                <!-- Delete Button -->
                <li>

                    <button class="dropdown-item delete-post-btn" data-id="{{ $post->id }}">
                        Delete Post
                    </button>
                </li>
            @elseif($post->status == 'archived')
                <!-- Delete Button -->
                <li>

                    <button class="dropdown-item delete-post-btn" data-id="{{ $post->id }}">
                        Delete Post
                    </button>
                </li>
            @endif
        @endif
    @endif
</ul>
</div>
