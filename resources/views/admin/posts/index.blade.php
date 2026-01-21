@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/adminposts.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="posts-content">
        <header class="posts-header">
            <h1 class="posts-title">{{ __('admin.posts_management') }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="posts-back-btn">{{ __('admin.back_to_dashboard') }}</a>
        </header>

        <div class="posts-search">
            <input type="text" id="post-search" placeholder="{{ __('admin.search_posts') }}" class="posts-search-input">
        </div>

        <div class="posts-section">
            <div class="posts-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin.id') }}</th>
                            <th>{{ __('admin.user') }}</th>
                            <th>{{ __('admin.content') }}</th>
                            <th>{{ __('admin.views') }}</th>
                            <th>{{ __('admin.created') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->user->name }}</td>
                                <td>{{ Str::limit($post->content, 50) }}</td>
                                <td>{{ $post->views }}</td>
                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.posts.delete', $post) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="posts-btn posts-btn-danger" onclick="return confirm('{{ __('admin.confirm_delete_post') }}')">{{ __('admin.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">{{ __('admin.no_posts_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="posts-pagination">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('post-search').addEventListener('input', function() {
            let search = this.value.toLowerCase();
            document.querySelectorAll('.posts-table tr').forEach(row => {
                let user = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
                let content = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
                if (user && content && (user.includes(search) || content.includes(search))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection