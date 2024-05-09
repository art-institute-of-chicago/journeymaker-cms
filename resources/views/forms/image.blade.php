@pushOnce('extra_css')
    <link href="/assets/twill/css/custom.css" rel="stylesheet" />
@endPushOnce

<div class="custom">
    <div class="mt-8 mb-3">Image</div>
    <div>
        <img
            class="block w-full"
            src="{{ $src }}"
            alt="Object thumbnail"
            onerror="this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'"
        />
    </div>
</div>
