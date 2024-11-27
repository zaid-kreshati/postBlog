@extends('layouts.PostBlug_header')
@section('content')
@section('title', __('Post Blug'))



        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form id="search-form" class="input-group mb-3 shadow-sm">
                        <input type="text" id="search-query" name="query" class="form-control" placeholder="Search for users or posts..." aria-label="Search" aria-describedby="search-button">
                        <button class="btn btn-primary" type="button" id="search-button"><i class="fas fa-search"></i></button>
                    </form>
                    <div class="btn-group d-flex justify-content-center mb-4" role="group" aria-label="Search Filters" id="search-filters">
                        <button type="button" class="btn btn-outline-primary search-filter-btn" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-primary search-filter-btn" data-filter="users">Users</button>
                        <button type="button" class="btn btn-outline-primary search-filter-btn" data-filter="posts">Posts</button>
                        <button type="button" class="btn btn-outline-primary search-filter-btn" data-filter="posts_with_photo">Posts with Photo</button>
                        <button type="button" class="btn btn-outline-primary search-filter-btn" data-filter="posts_with_video">Posts with Video</button>
                    </div>
                   @include('partials.search',['users' => null, 'posts' => null])
                </div>
            </div>
        </div>

         <!-- Loading Spinner -->
         <div id="loading-spinner" class="text-center" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>



<script>

$(document).on('click', '[data-user-id]', function() {
        let userId = $(this).data('user-id');
        if (userId) {
            window.location.href = `{{ route('profile.index', ['id' => '']) }}`.replace('profile', 'profile/' + userId);
        } else {
            Swal.fire({
                title: "Error",
                text: 'User ID is not defined.',
                icon: "error"
            });
        }
    });


    $(document).on('focus', '#search-query', function() {
        $('#search-filters').show();
    });

    $(document).ready(function() {


        let currentFilter = 'all';
        let searchResults = $('.search-results');

        $('#search-query').on('input', function() {
            performSearch();
        });

        $('.search-filter-btn').on('click', function() {
            currentFilter = $(this).data('filter');
            $('.search-filter-btn').removeClass('active');
            $(this).addClass('active');
            performSearch();
        });

        function performSearch() {
            // Reset pagination and loading states
        window.SearchLoader.currentPage = 1;
        window.SearchLoader.hasMorePages = true;
        window.SearchLoader.isLoading = false;

            const query = $('#search-query').val();
            const resultsList = $('.results-list');
            resultsList.empty();

            if (query.trim() === '') {
                searchResults.hide();
                window.SearchLoader.init();

                return;
            }
            else{
                searchResults.show();
            }

            let url;
            switch (currentFilter) {
                case 'users':
                    url = '{{ route('search.users') }}';
                    break;
                case 'posts':
                    url = '{{ route('search.all.posts') }}';
                    break;
                case 'posts_with_photo':
                    url = '{{ route('search.posts.with.photo') }}';
                    break;
                case 'posts_with_video':
                    url = '{{ route('search.posts.with.video') }}';
                    break;
                default:
                    url = '{{ route('search.all') }}';
            }

            $('#loading-spinner').show();

            $.ajax({
                url: url,
                type: 'GET',
                data: { query: query },
                success: function(response) {

                    searchResults.html(response.data);

                    $('#loading-spinner').hide();
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error",
                        text: 'An error occurred while processing your request.',
                        icon: "error"
                    });
                    console.error('Error details:', xhr.responseText);
                    $('#loading-spinner').hide();
                }
            });
        }

    });

    window.SearchLoader = {
            currentPage: 1,
            isLoading: false,
            hasMorePages: true,

            init: function() {
                if (!window.loadEndInitialized) {
                    this.attachScrollHandler();
                    window.loadEndInitialized = true;
                }
            },

            loadMoreResults: function(retryCount = 0) {
                if (this.isLoading || !this.hasMorePages) {
                    if (!this.hasMorePages) {
                        console.log('No more results');
                    }
                    return;
                }

                this.isLoading = true;

                let status = $('.search-filter-btn.active').data('filter');
                if (typeof status === 'undefined') {
                    status = 'all';
                }
                let query = $('#search-query').val();

                if (query.trim() === '') {
                    $('#loading-spinner').hide();
                    return;
                }

                $('#loading-spinner').show();
                $.ajax({
                    url: '{{ route('search.load-more') }}',
                    type: 'GET',
                    data: {
                        page: this.currentPage + 1,
                        filter: status,
                        query: query,
                    },
                    success: (response) => {
                        console.log("load");
                        if (response.data.trim() === '') {
                            this.hasMorePages = false;
                            console.log('no more pages');
                            $('#loading-spinner').hide();

                        } else {
                            console.log();
                            this.currentPage++;

                            $('.search-results').append(response.data);
                            this.hasMorePages = response.data.hasMorePages;

                        }

                        if (!this.hasMorePages) {
                            $('#loading-spinner').hide();
                        }
                    },
                    error: (xhr) => {
                        console.error('Error loading posts:', xhr.responseText);

                        if (retryCount < 3) {
                            setTimeout(() => {
                                this.isLoading = false;
                                this.loadMoreResults(retryCount + 1);
                            }, 1000 * (retryCount + 1));
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: 'Failed to load more results. Please try again later.',
                                icon: "error"
                            });
                            $('#loading-spinner').hide();
                        }
                    },
                    complete: () => {
                        $('#loading-spinner').hide();
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
                    let query = $('#search-query').val();
                    if (query.trim() === '') {
                        $('#loading-spinner').hide();
                        return;
                    }

                    if (!this.hasMorePages) {
                        $('#loading-spinner').hide();
                        return;
                    }

                    if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        this.loadMoreResults();
                    }
                }, 250));
            },






        };
         // Initialize when document is ready
         $(document).ready(function() {
                window.SearchLoader.init();
        });


</script>
@endsection
