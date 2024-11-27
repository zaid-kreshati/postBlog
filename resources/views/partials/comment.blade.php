 <div id="commentForm-{{ $post->id }}" style="display:none">
     @foreach ($post->comment as $comment)
         @if ($comment->parent_id != null)
             @continue
         @endif
         <div class="comments-section">
             <!-- Single Comment -->
             <div class="comment">
                 <div class="comment-header">
                     <!-- Profile Image -->
                     <div class="d-flex align-items-center" data-user-id="{{ $comment->user_id }}" style="cursor: pointer;">
                     @if ($comment->user->media->isNotEmpty())
                         @foreach ($comment->user->media as $media)

                             @if ($media->type == 'user_profile_image')
                                 <img src="{{ asset('storage/photos/' . $media->URL) }}" alt="Profile photo"
                                     class="img-fluid rounded-circle"
                                     style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">
                             @endif
                         @endforeach
                     @else
                         <img src="{{ asset('/PostBlug/default-profile.png') }}" alt="Profile photo"
                             class="img-fluid rounded-circle"
                             style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">
                     @endif

                     <div style="right: 750px;  font-size: 15px;   margin-top: -40px;  position: relative;">
                         {{ $comment->user->name }}
                     </div>
                     </div>
                 </div>

                 <div class="comment-content">
                     {{ $comment->text }}
                 </div>

                 <div class="comment-actions" style="right: 5%;">
                     <button class="comment-btn reply-btn" id="replyCommentBtn" data-comment-id="{{ $comment->id }}">
                         <i class="fas fa-reply" ></i>
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
                     id="nested-comment-{{ $comment->id }}"
                         rows="2">{{ old('nested_text') }}</textarea>
                 </form>


            </div>
     @endforeach

         </div>
         <!-- New Comment Form -->
         <div class="schedule-item" id="commentForm2-{{ $post->id }}" style="display: none;">
             <form id="commentForm-main" action="{{ route('comment.store') }}" method="POST">
                 @csrf
                 <label for="comment">{{ __(' Comment') }}:</label>
                 <input type="hidden" name="post_id" value="{{ $post->id }}">
                 <textarea name="text" class=" comment-input"
                     id="comment-textarea->{{ $post->id }}"
                     placeholder="Write a comment..." rows="2">{{ old('text') }}</textarea>
             </form>
         </div>


