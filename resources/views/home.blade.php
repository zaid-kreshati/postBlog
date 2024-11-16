@extends('layouts.PostBlug_header')
@section('title', __('Post Blug'))
@section('content')

<body>
<main>
    @include('partials.search')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @include('partials.posts')
                </div>
            </div>
        </div>
    </section>
</main>

<script>

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
            const status = @json($status)

            $.ajax({
                url: '{{ route('posts.load-more') }}',
                type: 'GET',
                data: {
                    page: this.currentPage + 1,
                    home: home,
                    status
                },
                success: (response) => {
                    if (response.data.html.trim() === '') {
                        this.hasMorePages = false;
                        $('#loading-spinner').hide();

                    } else {
                        this.currentPage++;
                        console.log(this.currentPage);
                        console.log(response.data.html);
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
                        alert('Failed to load more posts. Please try again later.');
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

    // Initialize when document is ready
    $(document).ready(function() {
        window.postLoader.init();
    });

    $(document).ready(function() {
        $('#search-query').on('input', function() {
            const query = $(this).val();
            const resultsList = $('#results-list');
            resultsList.empty();

            if (query.trim() === '') {
                $('#search-results').hide();
                return;
            }

            $.ajax({
                url: '{{ route('search.all') }}',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    console.log(response.data);
                    if (response.data.users.length || response.data.posts.length) {
                        $('#search-results').show();

                        response.data.users.forEach(function(user) {
                            let profileImage = user.media && user.media.length > 0 ?
                                user.media.find(media => media.type === 'user_profile_image') : null;
                            let imageUrl = profileImage ?
                                `{{ asset('storage/photos/') }}/${profileImage.URL}` :
                                '{{ asset('/PostBlug/default-profile .png') }}';

                            const boldedName = user.name.replace(new RegExp(query, 'gi'), (match) => `<strong>${match}</strong>`);
                            resultsList.append(`
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="${imageUrl}" alt="Profile photo" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: fill; margin-right: 10px;">
                                        <span><strong></strong> ${boldedName}</span>
                                    </div>
                                </li>
                            `);
                        });

                        response.data.posts.forEach(function(post) {
                            console.log(post)
                            let profileImage = post.user.media && post.user.media.length > 0 ?
                                post.user.media.find(media => media.type === 'user_profile_image') : null;
                            let imageUrl = profileImage ?
                                `{{ asset('storage/photos/') }}/${profileImage.URL}` :
                                '{{ asset('/PostBlug/default-profile .png') }}';

                            let postHtml = `
                                <div class="post">
                                    <div class="post-header">
                                        <div class="d-flex align-items-center">
                                            <img src="${imageUrl}" alt="Profile photo" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: fill; margin-right: 10px;">
                                            <span>${post.user.name}</span>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <p>${post.description.replace(new RegExp(query, 'gi'), (match) => `<strong>${match}</strong>`)}</p>
                                    </div>
                                    <div class="button-group">
                                        <button id="toggleCommentForm" class="btn-post" data-id="${post.id}">
                                            Comment
                                        </button>
                                    </div>
                                </div>
                            `;
                            resultsList.append(`<li class="list-group-item">${postHtml}</li>`);
                        });
                    } else {
                        resultsList.append('<li class="list-group-item">No results found.</li>');
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });

    
</script>
@endsection
</body>
