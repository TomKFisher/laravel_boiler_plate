@if( !empty($main_title) )
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <div class="quick-link-wrapper w-100 d-md-flex align-items-center flex-md-wrap">
                    <h4 class="page-title text-nowrap">{!! $main_title !!}</h4>
                    @if(!empty($header_links_left))
                        <ul class="quick-links-left">
                            @foreach($header_links_left as $link)
                                <li>
                                    <a href="{{$link['route'] != null ? isset($link['route_variables']) ? route($link['route'], $link['route_variables']) : route($link['route'] ) : '#'}}" {!! !empty($link['class']) ? 'class="'.$link['class'].'"' : '' !!}>{{$link['text']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if(!empty($header_links_right))
                        <ul class="quick-links-right ml-auto">
                            @foreach($header_links_right as $link)
                                <li>
                                    <a href="{{$link['route'] != null ? isset($link['route_variables']) ? route($link['route'], $link['route_variables']) : route($link['route']) : '#'}}" {!! !empty($link['class']) ? 'class="'.$link['class'].'"' : '' !!}>{{$link['text']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
