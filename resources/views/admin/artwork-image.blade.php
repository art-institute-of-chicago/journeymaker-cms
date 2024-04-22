<div @style(['width: 80px', 'height: 80px', 'background-color: #e5e7eb'])>
    <a href="{{ $link }}">
        <img
            src="{{ $src }}"
            @style(['width: 100%', 'height: 100%', 'object-fit: cover'])
            alt="Object thumbnail"
            onerror="this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'"
        />
    </a>
</div>
