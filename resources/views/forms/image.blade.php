<div class="image-wrapper" @style(['margin-top: 35px'])>
    <div @style(['display: block', 'margin-bottom: 10px'])>Image</div>
    <div>
        <img
            src="{{ $src }}"
            alt="Object thumbnail"
            onerror="this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'"
        />
    </div>
</div>
