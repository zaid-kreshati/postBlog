 <div id="commentForm-{{ $post->id }}" style="display:none">
     @foreach ($post->comment as $comment)
         @if ($comment->parent_id != null)
             @continue
         @endif
         <div class="comments-section">
             <!-- Single Comment -->
             <div class="comment">
                 <!-- Profile Image -->
                 @if ($comment->user->media->isNotEmpty())
                     @foreach ($comment->user->media as $media)
                             @if ($media->type == 'user_profile_image')
                                     <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                         class=" rounded-circle"
                                         data-user-id="{{ $comment->user_id }}"
                                         style="width: 60px; height: 60px; object-fit: fill;
                                     right: 320px; position: relative; cursor: pointer; top: 10px;">
                             @endif
                         @endforeach
                     @else
                         <img src="{{ asset('/PostBlug/default-profile.png') }}" alt="Profile photo"
                             class=" rounded-circle"
                             style="width: 60px; height: 60px; object-fit: fill;
                                 right: 320px; position: relative; cursor: pointer; top: 10px;">
                     @endif

                     <!-- Comment Username -->
                     <div style="right: 260px;  font-size: 25px;
                                bottom: 50px;  position: relative;">
                         {{ $comment->user->name }}
                     </div>
                     <!-- Comment Text -->
                     <div for="comment-content" class="comment-content">
                         {{ $comment->text }}
                     </div>

                     <div class="comment-actions" >
                        <button class="comment-btn reply-btn" id="replyCommentBtn" data-comment-id="{{ $comment->id }}">
                            <i class="fas fa-reply"></i>
                            <div>Reply</div>
                        </button>
                    </div>






             </div>

             <!-- Nested Comment Form -->
             <form action="{{ route('comment.store.nested') }}" method="POST" id="replyCommentForm-{{ $comment->id }}"
                 class="comment-form" style="display: none;">
                 @csrf
                 <div class="nested-comment-form">
                     @include('partials.nested-comment')
                 </div>

                 <label for="nested-comment">{{ __('Reply') }}:</label>
                 <input type="hidden" name="parent_id" id="comment-id" value="{{ $comment->id }}">
                 <input type="hidden" name="post_id" id="post-id" value="{{ $post->id }}">
                 <textarea name="nested_text" class="comment-input" placeholder="Write a comment..."
                     id="nested-comment-{{ $comment->id }}" rows="2">{{ old('nested_text') }}</textarea>
             </form>


         </div>
     @endforeach

 </div>
 <!-- New Comment Form -->
 <div class="schedule-item" id="commentForm2-{{ $post->id }}" style="display: none;">
     <form id="commentForm-main" action="{{ route('comment.store') }}" method="POST">
         @csrf
         <input type="hidden" name="post_id" value="{{ $post->id }}">
         <textarea name="text" class=" comment-input" id="comment-textarea->{{ $post->id }}"
             placeholder="Write a comment..." rows="2">{{ old('text') }}</textarea>
     </form>
 </div>
