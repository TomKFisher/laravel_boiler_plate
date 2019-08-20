@if( !empty( request()->session()->exists('success') ) )
    <div class="alert alert-success alert-dismissable fade show d-flex align-items-center flex-nowrap p-0">
        <div class="py-2 px-3 mr-3 border-right">
            <i class="fa fa-check-circle"></i>
        </div>
        <h6 class="py-2 mb-0">Success - {{ request()->session()->get('success') }}</h6>
        <button type="button" class="close p-2 ml-auto" data-dismiss="alert" aria-label="Close" style="font-size:1.2rem;">
            <span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
@if( !empty( request()->session()->exists('message') ) )
    <div class="alert alert-info alert-dismissable fade show d-flex align-items-center flex-nowrap p-0">
        <div class="py-2 px-3 mr-3 border-right">
            <i class="fa fa-info-circle"></i>
        </div>
        <h6 class="py-2 mb-0">{{ request()->session()->get('message') }}</h6>
        <button type="button" class="close p-2 ml-auto" data-dismiss="alert" aria-label="Close" style="font-size:1.2rem;">
            <span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
@if( !empty( request()->session()->exists('warning') ) )
    <div class="alert alert-warning alert-dismissable fade show d-flex align-items-center flex-nowrap p-0">
        <div class="py-2 px-3 mr-3 border-right">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <h6 class="py-2 mb-0">Warning - {{ request()->session()->get('warning') }}</h6>
        <button type="button" class="close p-2 ml-auto" data-dismiss="alert" aria-label="Close" style="font-size:1.2rem;">
            <span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
@if( !empty( request()->session()->exists('error') ) )
    <div class="alert alert-danger alert-dismissable fade show d-flex align-items-center flex-nowrap p-0">
        <div class="py-2 px-3 mr-3 border-right">
            <i class="fa fa-times-circle"></i>
        </div>
        <h6 class="py-2 mb-0">Error - {{ request()->session()->get('error') }}</h6>
        <button type="button" class="close p-2 ml-auto" data-dismiss="alert" aria-label="Close" style="font-size:1.2rem;">
            <span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
@if( !empty( request()->session()->exists('verified') ) )
    <div class="alert alert-success alert-dismissable fade show d-flex align-items-center flex-nowrap p-0">
        <div class="py-2 px-3 mr-3 border-right">
            <i class="fa fa-check-circle"></i>
        </div>
        <h6 class="py-2 mb-0">Success - Your email has been verified</h6>
        <button type="button" class="close p-2 ml-auto" data-dismiss="alert" aria-label="Close" style="font-size:1.2rem;">
            <span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
@if( !empty( request()->session()->exists('status') ) )
    <div class="alert alert-info alert-dismissable fade show d-flex align-items-center flex-nowrap p-0">
        <div class="py-2 px-3 mr-3 border-right">
            <i class="fa fa-info-circle"></i>
        </div>
        <h6 class="py-2 mb-0">{{ request()->session()->get('status') }}</h6>
        <button type="button" class="close p-2 ml-auto" data-dismiss="alert" aria-label="Close" style="font-size:1.2rem;">
            <span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
