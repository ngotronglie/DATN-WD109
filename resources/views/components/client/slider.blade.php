<!-- START SLIDER AREA -->
@if(isset($banners) && count($banners) > 0)
<div class="slider-area">
    <div class="bend niceties preview-1">
        <div id="ensign-nivoslider" class="slides">
            @foreach($banners as $banner)
            <img src="{{ asset('images/banners/' . $banner->img) }}" alt="{{ $banner->title }}" title="#slider-direction-{{ $loop->iteration }}"/>
            @endforeach
        </div>
        
        @foreach($banners as $banner)
        <!-- direction {{ $loop->iteration }} -->
        <div id="slider-direction-{{ $loop->iteration }}" class="t-cn slider-direction">
            <div class="slider-progress"></div>
            <div class="slider-content t-cn s-tb slider-1">
                <div class="title-container s-tb-c title-compress">
                    <h2 class="title1">{{ $banner->title }}</h2>
                    <h3 class="title2">{{ $banner->description }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<!-- END SLIDER AREA -->