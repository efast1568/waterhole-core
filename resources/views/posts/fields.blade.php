<x-waterhole::field
    name="title"
    label="Title"
    description="Be specific and imagine you’re asking a question to another person"
>
    <input
        id="{{ $component->id }}"
        name="title"
        type="text"
        value="{{ old('title', $post->title ?? '') }}"
        class="input"
    >
</x-waterhole::field>

<x-waterhole::field
    name="body"
    label="Body"
    description="Include all the information someone would need to answer your question"
>
    <div class="input composer">
        <textarea name="body" id="{{ $component->id }}">{{ old('body', $post->body ?? '') }}</textarea>
    </div>
</x-waterhole::field>
