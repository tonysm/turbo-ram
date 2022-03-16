<meta name="current-user-id" content="{{ Auth::id() }}" />
<meta name="current-user-name" content="{{ Auth::user()->name }}" />
<meta name="current-user-image-url" content="{{ Auth::user()->profile_photo_url }}" />
<meta name="current-user-current-bucket-id" content="{{ Auth::user()->currentTeam->bucket->id }}" />
<meta name="current-user-blog-id" content="{{ Auth::user()->currentTeam->bucket->recordings()->blog()->first()->id }}" />
