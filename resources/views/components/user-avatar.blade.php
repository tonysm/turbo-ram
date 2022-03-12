@props(["user"])

<img {{ $attributes->merge(["class" => "object-cover w-8 h-8 rounded-full"]) }} src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
